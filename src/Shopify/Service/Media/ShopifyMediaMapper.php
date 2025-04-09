<?php

namespace App\Shopify\Service\Media;

use App\Shopify\Model\Media\CreateMediaInput;
use App\Shopify\Model\Media\CreateMediaInputs;
use App\Shopify\Model\Media\MediaContentType;
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
                $image = $hotSpotImage->getImage();
                $createMediaInput = new CreateMediaInput(Tool::getHostUrl() . $image->getFrontendFullPath(), new MediaContentType(), $image->getFilename());
                $createMediaInputs->addMediaInput($createMediaInput);
            }
        } else if ($property === 'image') {
            $image = $object->getImage();
            $createMediaInput = new CreateMediaInput(Tool::getHostUrl() . $image->getFrontendFullPath(), new MediaContentType(), $image->getFilename());
            $createMediaInputs->addMediaInput($createMediaInput);
        }

        return $createMediaInputs;
    }
}
