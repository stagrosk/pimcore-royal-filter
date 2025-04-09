<?php

namespace App\Shopify\Service\Product;

use App\Shopify\Abstract\AbstractShopifyService;
use App\Shopify\Exception\IgnoreDataObjectMappingException;
use App\Shopify\Model\Product\ShopifyProduct;
use Doctrine\DBAL\Connection;
use Exception;
use Pimcore\Model\DataObject\Concrete;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Throwable;
use Traversable;

class ShopifyProductService extends AbstractShopifyService
{
    /** @var IShopifyProductMapper[] $productMappers */
    private array $productMappers;

    /**
     * @param \Doctrine\DBAL\Connection $connection
     * @param \Psr\Log\LoggerInterface $logger
     * @param iterable $productMappers
     */
    public function __construct(
        Connection      $connection,
        LoggerInterface $logger,
        #[TaggedIterator(tag: IShopifyProductMapper::MAPPER_TAG, indexAttribute: 'key')]
        iterable        $productMappers)
    {
        parent::__construct($connection, $logger);
        $this->productMappers = $productMappers instanceof Traversable ? iterator_to_array($productMappers) : $productMappers;
    }

    /**
     * @param string $mapperServiceKey
     * @param int $limit
     *
     * @throws \Doctrine\DBAL\Exception
     * @throws \Exception
     * @return array
     */
    public function getProductsToSync(string $mapperServiceKey, int $limit): array
    {
        $mapperService = $this->getMapperService($mapperServiceKey);
        $productClassId = $mapperService->getProductClassId();

        $productIds = $this->getProductIds($productClassId, $mapperServiceKey, $mapperService->getShopifyChannelKey());

        $newModificationDate = null;
        $mappedProducts = [];
        foreach ($productIds as $productId) {
            try {
                $product = Concrete::getById($productId[self::ALIAS_ID], ['force' => true]);
                $shopifyModelArray = $this->getMappedProductArray($mapperService, $product);

                if ($this->upsertProductEtag($productId[self::ALIAS_ID], $shopifyModelArray, $mapperServiceKey)) {
                    $mappedProducts[] = $shopifyModelArray;
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
     * @return \App\Shopify\Service\Product\IShopifyProductMapper
     */
    private function getMapperService(string $mapperServiceKey): IShopifyProductMapper
    {
        $service = current(array_filter($this->productMappers, function ($productMapper) use ($mapperServiceKey) {
            return $productMapper->getMapperServiceKey() === $mapperServiceKey;
        }));

        if (empty($service)) {
            throw new Exception("Unable to find a mapper service with key equal to '$mapperServiceKey',
                please check if the service is registered with the correct key");
        }

        return $service;
    }

    /**
     * @param \App\Shopify\Service\Product\IShopifyProductMapper $mapperService
     * @param \Pimcore\Model\DataObject\Concrete $product
     *
     * @return array
     */
    private function getMappedProductArray(IShopifyProductMapper $mapperService, Concrete $product): array
    {
        $shopifyProductModel = new ShopifyProduct();
        $shopifyProductModel = $mapperService->getMappedObject($shopifyProductModel, $product);
        return $shopifyProductModel->getAsArray();
    }
}
