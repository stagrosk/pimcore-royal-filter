<?php

namespace App\Shopify\Service\Translation;

use App\Shopify\Model\Translation\TranslationInputs;
use Pimcore\Model\DataObject\AbstractObject;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: self::MAPPER_TAG)]
interface IShopifyTranslationMapper
{
    const MAPPER_TAG = 'shopify_translation_mapper';

    public function getMappedObject(TranslationInputs $inputs, AbstractObject $object): TranslationInputs;
}
