<?php

namespace App\Shopify\Service\Price;

use App\Shopify\Abstract\AbstractShopifyService;
use App\Shopify\Exception\IgnoreDataObjectMappingException;
use App\Shopify\Model\Price\ShopifyPrice;
use Doctrine\DBAL\Connection;
use Exception;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Product;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Throwable;
use Traversable;

class ShopifyPriceService extends AbstractShopifyService
{
    /** @var IShopifyPriceMapper[] $productMappers */
    private array $priceMappers;

    /**
     * @param \Doctrine\DBAL\Connection $connection
     * @param \Psr\Log\LoggerInterface $logger
     * @param iterable $priceMappers
     */
    public function __construct(
        Connection $connection,
        LoggerInterface $logger,
        #[TaggedIterator(tag: IShopifyPriceMapper::MAPPER_TAG, indexAttribute: 'key')]
        iterable $priceMappers
    ) {
        parent::__construct($connection, $logger);
        $this->priceMappers = $priceMappers instanceof Traversable ? iterator_to_array($priceMappers) : $priceMappers;
    }

    /**
     * @param string $mapperServiceKey
     * @param int $limit
     *
     * @throws \Doctrine\DBAL\Exception
     * @throws \Exception
     * @return array
     */
    public function getPricesToSync(string $mapperServiceKey, int $limit): array
    {
        $mapperService = $this->getMapperService($mapperServiceKey);
        $productClassId = $mapperService->getProductClassId();

        $productIds = $this->getProductIds($productClassId, $mapperServiceKey, $mapperService->getShopifyChannelKey());

        $newModificationDate = null;
        $mappedProducts = [];
        foreach ($productIds as $productId) {
            try {
                $product = Concrete::getById($productId[self::ALIAS_ID], ['force' => true]);
                /** @var Product[] $variants */
                $variants = $product->getChildren([AbstractObject::OBJECT_TYPE_VARIANT])->load();
                if (!empty($variants)) {
                    foreach ($variants as $variant) {
                        $shopifyModelArray = $this->getMappedPriceArray($mapperService, $variant);

                        if ($this->upsertProductEtag($variant->getId(), $shopifyModelArray, $mapperServiceKey)) {
                            $mappedProducts[] = $shopifyModelArray;
                        }
                    }
                } else {
                    $shopifyModelArray = $this->getMappedPriceArray($mapperService, $product);

                    if ($this->upsertProductEtag($product->getId(), $shopifyModelArray, $mapperServiceKey)) {
                        $mappedProducts[] = $shopifyModelArray;
                    }
                }

                $newModificationDate = $productId[self::ALIAS_MOST_RECENT_MODIFICATION_DATE];
                if (count($mappedProducts) == $limit) {
                    break;
                }
            } catch (IgnoreDataObjectMappingException) {
                // Do nothing
            } catch (Throwable $th) {
                $this->logger->error("Error mapping product id: {$productId[self::ALIAS_ID]}, mapper service key: {$mapperServiceKey},
                error message: {$th->getMessage()}");
            }
        }

        $this->upsertLastModificationDate($mapperServiceKey, $newModificationDate);

        return $mappedProducts;
    }

    /**
     * @param string $mapperServiceKey
     *
     * @throws \Exception
     * @return \App\Shopify\Service\Price\IShopifyPriceMapper
     */
    private function getMapperService(string $mapperServiceKey): IShopifyPriceMapper
    {
        $service = current(array_filter($this->priceMappers, function ($priceMapper) use ($mapperServiceKey) {
            return $priceMapper->getMapperServiceKey() === $mapperServiceKey;
        }));

        if (empty($service)) {
            throw new Exception("Unable to find a mapper service with key equal to '$mapperServiceKey',
                please check if the service is registered with the correct key");
        }

        return $service;
    }

    /**
     * @param \App\Shopify\Service\Price\IShopifyPriceMapper $mapperService
     * @param \Pimcore\Model\DataObject\Concrete $product
     *
     * @return array
     */
    private function getMappedPriceArray(IShopifyPriceMapper $mapperService, Concrete $product): array
    {
        $shopifyPriceModel = new ShopifyPrice();
        $shopifyPriceModel = $mapperService->getMappedPrice($shopifyPriceModel, $product);
        return $shopifyPriceModel->getAsArray();
    }
}
