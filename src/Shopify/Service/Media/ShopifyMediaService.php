<?php

namespace App\Shopify\Service\Media;

use App\Shopify\Graphql\Mutation\FIle\FileDeleteMutation;
use App\Shopify\Graphql\Query\Product\ProductMediasQuery;
use Pimcore\Model\Asset\Image;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Data\ImageGallery;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Service;
use Pimcore\Model\DataObject\ShopifyMedia;

readonly class ShopifyMediaService
{
    public const MEDIA_FOLDER = 'Media';

    /**
     * @param \App\Shopify\Graphql\Query\Product\ProductMediasQuery $productMediasQuery
     * @param \App\Shopify\Graphql\Mutation\FIle\FileDeleteMutation $fileDeleteMutation
     */
    public function __construct(
        private ProductMediasQuery $productMediasQuery,
        private FileDeleteMutation $fileDeleteMutation,
    ) {
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject|\Pimcore\Model\DataObject\Product $object
     *
     * @throws \Exception
     * @return void
     */
    public function processMedia(AbstractObject|Product $object): void
    {
        $shopifyMediaToBeDeleted = [];

        if ($object instanceof Product) {
            // get shopify product media
            $shopifyMediaIndexed = $this->getShopifyProductMediaIndexed($object);

            // loop all medias from shopify
            foreach ($shopifyMediaIndexed as $node) {
                // find image on product by image name
                $image = $this->searchImageByName($node['alt'], $object->getImageGallery());

                // found image
                if ($image instanceof Image) {
                    $shopifyImagePath = $this->getShopifyImageFullPath($image, $object);
                    $shopifyMedia = ShopifyMedia::getByPath($shopifyImagePath, ['force' => true]);
                    if (!$shopifyMedia instanceof ShopifyMedia) {
                        $shopifyMedia = ShopifyMedia::getByApiId($node['id'], 1);
                    }

                    // not found -> create
                    if (!$shopifyMedia instanceof ShopifyMedia) {
                        $this->processProductShopifyMedia($image, $object, $shopifyMedia, $node['id']);
                    }
                } else {
                    // not found image -> delete from shopify
                    $shopifyMediaToBeDeleted[] = $node['id'];
                }
            }
        }

        if (!empty($shopifyMediaToBeDeleted)) {
            $response = $this->fileDeleteMutation->callAction($shopifyMediaToBeDeleted);
            $data = $response['data']['fileDelete'];
            if (!empty($data['userErrors'])) {
                throw new \Exception($data['userErrors'][0]['message']);
            }

            foreach ($shopifyMediaToBeDeleted as $shopifyMediaId) {
                // try to get by apiId ... if found -> delete
                $shopifyMedia = ShopifyMedia::getByApiId($shopifyMediaId, 1);
                if ($shopifyMedia instanceof ShopifyMedia) {
                    $shopifyMedia->delete();
                }
            }
        }
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @throws \Exception
     * @return array
     */
    public function getUnprocessedImages(AbstractObject $object): array
    {
        $unprocessedImages = [];

        if ($object instanceof Product) {
            // get all images from the shopify product
            $shopifyMediaIndexed = $this->getShopifyProductMediaIndexed($object);

            // loop product images and check if there is any image that was not sent to shopify
            $imageGallery = $object->getImageGallery();
            foreach ($imageGallery->getItems() as $item) {
                $image = $item->getImage();

                // check if exists in shopify if yes -> continue
                foreach ($shopifyMediaIndexed as $node) {
                    if ($node['alt'] === $image->getFilename()) {
                        continue 2;
                    }
                }

                // not found so add as unprocessed
                $unprocessedImages[] = $image;
            }
        }

        return $unprocessedImages;
    }

    /**
     * @param \Pimcore\Model\DataObject\Product $object
     *
     * @throws \Exception
     * @return array
     */
    private function getShopifyProductMediaIndexed(Product $object): array
    {
        $shopifyMediaIndexed = [];

        // get shopify product media
        $response = $this->productMediasQuery->callAction($object);

        // loop all medias from shopify and check if exists on the product
        $data = $response['data']['product'];

        // loop all medias from shopify
        foreach ($data['media']['edges'] as $edge) {
            $shopifyMediaIndexed[] = $edge['node'];
        }

        return $shopifyMediaIndexed;
    }

    /**
     * @param \Pimcore\Model\Asset\Image $image
     * @param \Pimcore\Model\DataObject\Product $product
     * @param \Pimcore\Model\DataObject\ShopifyMedia|null $shopifyMedia
     * @param string|null $apiId
     *
     * @throws \Exception
     * @return void
     */
    private function processProductShopifyMedia(Image $image, Product $product, ?ShopifyMedia $shopifyMedia = null, ?string $apiId = null): void
    {
        if (!$shopifyMedia instanceof ShopifyMedia) {
            $shopifyMedia = new ShopifyMedia();
        }

        $shopifyMedia->setPublished(true);
        $shopifyMedia->setKey(Service::getValidKey($this->getShopifyImageKey($image, $product), 'object'));
        $shopifyMedia->setParent(Service::createFolderByPath(sprintf('%s/%s', $product->getFullPath(), self::MEDIA_FOLDER)));
        $shopifyMedia->setApiId($apiId);
        $shopifyMedia->setImage($image);
        $shopifyMedia->setProduct($product);
        $shopifyMedia->save();
    }

    /**
     * @param \Pimcore\Model\Asset\Image $image
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @return string
     */
    private function getShopifyImageFullPath(Image $image, AbstractObject $object): string
    {
        $folder = sprintf('%s/%s', $object->getFullPath(), self::MEDIA_FOLDER);

        return sprintf('%s/%s', $folder, $this->getShopifyImageKey($image, $object));
    }

    /**
     * @param \Pimcore\Model\Asset\Image $image
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @return string
     */
    private function getShopifyImageKey(Image $image, AbstractObject $object): string
    {
        return sprintf('%s-%s-%s', 'image', $object->getId(), $image->getId());
    }

    /**
     * @param string $name
     * @param \Pimcore\Model\DataObject\Data\ImageGallery $imageGallery
     *
     * @return \Pimcore\Model\Asset\Image|null
     */
    private function searchImageByName(string $name, ImageGallery $imageGallery): Image|null
    {
        // loop all items in the imageGallery and search by name
        foreach ($imageGallery->getItems() as $item) {
            $image = $item->getImage();
            if ($image->getFilename() === $name) {
                return $image;
            }
        }

        return null;
    }
}
