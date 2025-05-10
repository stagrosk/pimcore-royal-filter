<?php

namespace App\Shopify\Service\Media;

use App\Shopify\Model\Media\CreateMediaInput;
use App\Shopify\Model\Media\CreateMediaInputs;
use App\Shopify\Model\Media\MediaContentType;
use Pimcore\Model\Asset\Image;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Tool;

class ShopifyMediaMapper implements IShopifyMediaMapper
{
    const CLASS_ID = 'Asset';
    const SHOPIFY_CHANNEL_KEY = 'shopify_1';

    public function getMapperServiceKey(): string
    {
        return IShopifyMediaMapper::MAPPER_TAG;
    }

    public function getObjectClassId(): string
    {
        return self::CLASS_ID;
    }

    public function getShopifyChannelKey(): string
    {
        return self::SHOPIFY_CHANNEL_KEY;
    }

    /**
     * @param \App\Shopify\Model\Media\CreateMediaInputs $createMediaInputs
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     * @param string $property
     *
     * @return \App\Shopify\Model\Media\CreateMediaInputs
     */
    public function getMappedObject(CreateMediaInputs $createMediaInputs, AbstractObject $object, string $property = 'imageGallery'): CreateMediaInputs
    {
        if ($property === 'imageGallery') {
            $imageGallery = $object->getImageGallery();
            foreach ($imageGallery->getItems() as $hotSpotImage) {
                $createMediaInputs->addMediaInput($this->createMediaInput($hotSpotImage->getImage()));
            }
        } else if ($property === 'image') {
            $createMediaInputs->addMediaInput($this->createMediaInput($object->getImage()));
        }

        return $createMediaInputs;
    }

    /**
     * @param \App\Shopify\Model\Media\CreateMediaInputs $createMediaInputs
     * @param \Pimcore\Model\Asset\Image[] $images
     *
     * @return \App\Shopify\Model\Media\CreateMediaInputs
     */
    public function getMappedImages(CreateMediaInputs $createMediaInputs, array $images): CreateMediaInputs
    {
        foreach ($images as $image) {
            $createMediaInputs->addMediaInput($this->createMediaInput($image));
        }

        return $createMediaInputs;
    }

    /**
     * @param \Pimcore\Model\Asset\Image $image
     *
     * @return \App\Shopify\Model\Media\CreateMediaInput
     */
    private function createMediaInput(Image $image) : CreateMediaInput
    {
        return new CreateMediaInput(Tool::getHostUrl() . $image->getFrontendFullPath(), new MediaContentType(), $image->getFilename());
    }
}
