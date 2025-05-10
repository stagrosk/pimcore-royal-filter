<?php

namespace App\Shopify\Service\Metafields;

use App\Model\ClassificationStoreMappingItem;
use App\Pimcore\ClassificationStore\ClassificationStoreHelper;
use App\Pimcore\Helpers\VersionHelper;
use App\Pimcore\Model\DataObject\Category;
use App\Shopify\Model\Metafields\MetafieldMetaTypeEnum;
use App\Shopify\Model\Metafields\MetafieldOwnerTypeEnum;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Classificationstore\KeyConfig;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\QuantityValue\Unit;
use Pimcore\Model\DataObject\Service;
use Pimcore\Model\DataObject\ShopifyMetafieldDefinition;

readonly class ShopifyMetafieldService
{
    /**
     * @param \App\Pimcore\ClassificationStore\ClassificationStoreHelper $classificationStoreHelper
     */
    public function __construct(
        private ClassificationStoreHelper $classificationStoreHelper,
    ) {
    }

    /**
     * possible to implement metafields on: variants, collections, customers, orders, draft orders, locations, pages, blogs, blog posts, markets
     *
     * @param \Pimcore\Model\DataObject\AbstractObject|\Pimcore\Model\DataObject\Product $object
     *
     * @throws \Exception
     * @return array
     */
    public function getObjectMetafieldDefinitions(AbstractObject|Product $object): array
    {
        $metafieldDefinitions = [];
        $processedMetafieldDefinitionIds = [];

        // get metadata from object - classificationStore
        $metadata = $object->getMetadata();

        // get mapped metadata
        $classificationStoreMapping = $this->classificationStoreHelper->getClassificationStoreMapped($metadata);

        $className = $object->getClassName();
        $ownerType = MetafieldOwnerTypeEnum::from(strtoupper($className)); //Product/Collection

        // loop all items in product metadata
        foreach ($classificationStoreMapping->getClassificationStoreMappingItems() as $classificationStoreMappingItem) {

            $keyConfig = $classificationStoreMappingItem->getKeyConfig();
            $groupConfig = $classificationStoreMappingItem->getGroupConfig();
            $ident = sprintf('class-%s/groupConfigId-%s/keyConfigId-%s', $className, $groupConfig->getId(), $keyConfig->getId());

            // get by classificationStoreIdent - it is combination of object class and classification store keyConfig id
            $metafieldDefinition = ShopifyMetafieldDefinition::getByClassificationStoreIdent($ident, 1);
            if (!$metafieldDefinition instanceof ShopifyMetafieldDefinition) {
                $folderPath = sprintf('Shopify/Metafields/%s', $className);

                $metafieldDefinition = new ShopifyMetafieldDefinition();
                $metafieldDefinition->setPublished(true);
                $metafieldDefinition->setKey(Service::getValidKey(sprintf('%s-%s', $groupConfig->getName(), $keyConfig->getName()), 'object'));
                $metafieldDefinition->setParent(Service::createFolderByPath($folderPath));
                $metafieldDefinition->setClassificationStoreIdent($ident);

                $metafieldDefinition->setName(ucfirst(sprintf('%s - %s', $groupConfig->getName(), $keyConfig->getTitle())));
                $metafieldDefinition->setNamespace(strtolower($className));
                $metafieldDefinition->setMetaKey(strtolower(sprintf('%s_%s_%s', $metafieldDefinition->getNamespace(), $groupConfig->getName(), $keyConfig->getName())));
                $metafieldDefinition->setMetaType($this->resolveType($keyConfig));
                $metafieldDefinition->setDescription($keyConfig->getDescription());
                $metafieldDefinition->setOwnerType($ownerType->value);

                // through listener -> push to shopify so it will save API ID on definition
                VersionHelper::useVersioning(function () use ($metafieldDefinition) {
                    $metafieldDefinition->save();
                }, false);
            }

            $metafieldDefinitions['addMetafields'][$metafieldDefinition->getId()] = [
                'classificationStoreMappingItem' => $classificationStoreMappingItem,
                'metafieldDefinition' => $metafieldDefinition,
            ];

            // add id to array
            $processedMetafieldDefinitionIds[] = $metafieldDefinition->getId();
        }

        // add all missing items to set them on product as empty value
        return $this->addMetafieldsToBeDeleted($processedMetafieldDefinitionIds, $metafieldDefinitions, $ownerType);
    }

    /**
     * @param array $processedMetafieldDefinitionIds
     * @param array $metafieldDefinitions
     * @param \App\Shopify\Model\Metafields\MetafieldOwnerTypeEnum $ownerType
     *
     * @throws \Exception
     * @return array
     */
    private function addMetafieldsToBeDeleted(
        array $processedMetafieldDefinitionIds,
        array $metafieldDefinitions,
        MetafieldOwnerTypeEnum $ownerType
    ): array {
        $list = ShopifyMetafieldDefinition::getList();
        $list->setUnpublished(true); // get even it is unpublished because we want to clear value on the product
        if (!empty($processedMetafieldDefinitionIds)) {
            $condition = [];
            foreach ($processedMetafieldDefinitionIds as $metafieldDefinitionId) {
                $condition[] = 'id != ' . $metafieldDefinitionId;
            }
            $list->addConditionParam(implode(' AND ', $condition));
        }
        $list->addConditionParam('ownerType = ?', $ownerType->value);

        foreach ($list->getObjects() as $metafieldDefinition) {
            $metafieldDefinitions['deleteMetafields'][] = [
                'key' => $metafieldDefinition->getMetaKey(),
                'namespace' => $metafieldDefinition->getNamespace(),
            ];
        }

        return $metafieldDefinitions;
    }

    /**
     * @param \Pimcore\Model\DataObject\Classificationstore\KeyConfig $keyConfig
     *
     * @throws \Exception
     * @return string
     */
    private function resolveType(KeyConfig $keyConfig): string
    {
        $keyConfigDefinition = json_decode($keyConfig->getDefinition(), true);

        switch ($keyConfig->getType()) {
            case 'input':
            case 'select':
                $metaType = MetafieldMetaTypeEnum::from('SINGLE_LINE_TEXT_FIELD');
                break;

            case 'numeric':
                if ($keyConfigDefinition['integer'] === true) {
                    $metaType = MetafieldMetaTypeEnum::from('NUMBER_INTEGER');
                } else {
                    $metaType = MetafieldMetaTypeEnum::from('NUMBER_DECIMAL');
                }
                break;

            case 'quantityValue':
//            case 'quantityValueRange':
                $unit = Unit::getById($keyConfigDefinition['defaultUnit']); // default unit on keyConfig
                if (!$unit instanceof Unit) {
                    throw new \Exception(sprintf('[ShopifyMetafieldService -> resolveType] KeyConfig with ID: %s is type of quantity value and dont have default unit defined. It is required for shopify metafield definition resolver. Add it!', $keyConfig->getId()));
                }

                // dimensions
                if (in_array($unit->getAbbreviation(), ['in', 'ft', 'yd', 'mm', 'cm', 'm'], true)) {
                    $metaType = MetafieldMetaTypeEnum::from('DIMENSION');
                } elseif (in_array($unit->getAbbreviation(), ['oz', 'lb', 'g', 'kg'], true)) {
                    $metaType = MetafieldMetaTypeEnum::from('WEIGHT');
                } elseif (in_array($unit->getAbbreviation(), ['ml', 'cl', 'l', 'm3', 'us_fl_oz', 'us_pt', 'us_qt', 'us_gal', 'imp_fl_oz', 'imp_pt', 'imp_qt', 'imp_gal'], true)) {
                    $metaType = MetafieldMetaTypeEnum::from('VOLUME');
                } else {
                    // unsupported W, kwh...
                    $metaType = MetafieldMetaTypeEnum::from('NUMBER_DECIMAL');
//                    throw new \Exception(sprintf('[ShopifyMetafieldService -> resolveType] Cannot resolve unit abbreviation: %s for quantity value: %s, Check it!', $unit->getAbbreviation(), $keyConfig->getName()));
                }
                break;

            case 'checkbox':
                $metaType = MetafieldMetaTypeEnum::from('BOOLEAN');
                break;

            case 'textarea':
                $metaType = MetafieldMetaTypeEnum::from('MULTI_LINE_TEXT_FIELD');
                break;

            case 'date':
                $metaType = MetafieldMetaTypeEnum::from('DATE');
                break;

            case 'datetime':
                $metaType = MetafieldMetaTypeEnum::from('DATE_TIME');
                break;

            case 'rgbaColor':
                $metaType = MetafieldMetaTypeEnum::from('COLOR');
                break;

            default:
                $metaType = MetafieldMetaTypeEnum::from('SINGLE_LINE_TEXT_FIELD');
        }

        return $metaType->value;
    }

    /**
     * @param \Pimcore\Model\DataObject\ShopifyMetafieldDefinition $metafieldDefinition
     * @param \App\Model\ClassificationStoreMappingItem $classificationStoreMappingItem
     *
     * @return string|array
     */
    public function prepareValue(
        ShopifyMetafieldDefinition     $metafieldDefinition,
        ClassificationStoreMappingItem $classificationStoreMappingItem
    ): string|array {
        $metaType = $metafieldDefinition->getMetaType();
        switch ($metaType) {
            case 'LINK';
                $link = explode('|', $classificationStoreMappingItem->getValue());
                $value = [
                    'text' => $link[0],
                    'url' => $link[1],
                ];
                break;
            case 'MONEY';
                $value = [
                    'value' => $classificationStoreMappingItem->getValue(),
                    'currency_code' => $classificationStoreMappingItem->getUnit(),
                ];
                break;
            case 'RATING';
                // TODO: finish range if necessary
                $value = [
                    'value' => (float)$classificationStoreMappingItem->getValue(),
                    'scale_min' => 1.0,
                    'scale_max' => 5.0,
                ];
                break;
            case 'NUMBER_DECIMAL';
                $value = (float)$classificationStoreMappingItem->getValue();
                break;
            case 'NUMBER_INTEGER';
                $value = (int)$classificationStoreMappingItem->getValue();
                break;
            case 'DIMENSION';
            case 'VOLUME';
            case 'WEIGHT';
                $value = [
                    'value' => $classificationStoreMappingItem->getRawValue(),
                    'unit' => $classificationStoreMappingItem->getUnit(),
                ];
                break;
            default:    // BOOLEAN, COLOR, ID, SINGLE_LINE_TEXT_FIELD, URL, MULTI_LINE_TEXT_FIELD, JSON, DATE, DATE_TIME
                $value = $classificationStoreMappingItem->getValue();
        }

        return is_array($value) ? json_encode($value) : $value ?? '';
    }

    /**
     * @param \App\Pimcore\Model\DataObject\Category|\Pimcore\Model\DataObject\Product|\Pimcore\Model\DataObject\AbstractObject $object
     *
     * @throws \PHPShopify\Exception\ApiException
     * @throws \PHPShopify\Exception\CurlException
     * @throws \Exception
     * @return array
     */
    public function getMetafieldsToBeDeleted(Category|Product|AbstractObject $object): array
    {
        $metafieldIds = [];

        // possible to implement: variants, collections, customers, orders, draft orders, locations, pages, blogs, blog posts, markets
        if ($object instanceof Product) {
            // get mapped product
            $metafieldDefinitions = $this->getObjectMetafieldDefinitions($object);
            $metafieldIds = $metafieldDefinitions['deleteMetafields'] ?? [];
        }

        return $metafieldIds;
    }
}
