<?php

namespace App\Service\Generator\Mapper;

use App\Pimcore\ClassificationStore\ClassificationStoreHelper;
use App\Pimcore\ClassificationStore\ClassificationStoreService;
use App\Pimcore\Model\DataObject\Category;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Body;
use Pimcore\Model\DataObject\Center;
use Pimcore\Model\DataObject\Classificationstore;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Equipment;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\RoyalFilter;
use Pimcore\Tool;
use Pimcore\Translation\Translator;

abstract class BaseMapper implements MapperInterface
{
    public const COUNTRY_CZECHIA = 'Czechia';
    public const COUNTRY_SLOVAKIA = 'Slovakia';

    public const CATEGORY_FILTERS = 'RoyalFilters';

    public const CATEGORY_FILTERS_BY_WHIRLPOOLS = 'RoyalFiltersByWhirlpools';

    public const SHOPIFY_GOOGLE_CATEGORY_POOL_SPA_FILTERS = 'gid://shopify/TaxonomyCategory/hg-18-1-3';

    /**
     * @param \Pimcore\Translation\Translator $translator
     * @param \App\Pimcore\ClassificationStore\ClassificationStoreHelper $classificationStoreHelper
     * @param \App\Pimcore\ClassificationStore\ClassificationStoreService $classificationStoreService
     */
    public function __construct(
        protected readonly Translator $translator,
        protected readonly ClassificationStoreHelper $classificationStoreHelper,
        protected readonly ClassificationStoreService $classificationStoreService
    ) {
    }

    /**
     * @param \Pimcore\Model\DataObject\Product $product
     * @param string $categoryPath
     *
     * @return void
     */
    public function handleCategories(Product $product, string $categoryPath): void
    {
        $category = Category::getByPath($categoryPath, ['force' => true]);
        $categories = [];
        while ($category instanceof Category) {
            $categories[] = $category;
            $category = $category->getParent();
        }

        $product->setCategories($categories);
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @return array
     */
    public function getMappedParameters(AbstractObject $object): array
    {
        $params = [];

        if ($object instanceof RoyalFilter) {
            if ($object->getBody1() instanceof Body) {
                $params['body1'] = [
                    'classificationStoreItems' => $object->getBody1()->getMetadata()?->getItems(),
                    'mappedValues' => $this->classificationStoreHelper->getClassificationStoreMapped($object->getBody1()->getMetadata()),
                ];
            }
            if ($object->getBody2() instanceof Body) {
                $params['body2'] = [
                    'classificationStoreItems' => $object->getBody2()->getMetadata()?->getItems(),
                    'mappedValues' => $this->classificationStoreHelper->getClassificationStoreMapped($object->getBody2()->getMetadata()),
                ];
            }
            if ($object->getCenterBody1() instanceof Center) {
                $params['center1'] = [
                    'classificationStoreItems' => $object->getCenterBody1()->getMetadata()?->getItems(),
                    'mappedValues' => $this->classificationStoreHelper->getClassificationStoreMapped($object->getCenterBody1()->getMetadata()),
                ];
            }
            if ($object->getCenterBody2() instanceof Center) {
                $params['center2'] = [
                    'classificationStoreItems' => $object->getCenterBody2()->getMetadata()?->getItems(),
                    'mappedValues' => $this->classificationStoreHelper->getClassificationStoreMapped($object->getCenterBody2()->getMetadata()),
                ];
            }
            if ($object->getEquipBody1() instanceof Equipment) {
                $params['equip1'] = [
                    'classificationStoreItems' => $object->getEquipBody1()->getMetadata()?->getItems(),
                    'mappedValues' => $this->classificationStoreHelper->getClassificationStoreMapped($object->getEquipBody1()->getMetadata()),
                ];
            }
            if ($object->getEquipBody2() instanceof Equipment) {
                $params['equip2'] = [
                    'classificationStoreItems' => $object->getEquipBody2()->getMetadata()?->getItems(),
                    'mappedValues' => $this->classificationStoreHelper->getClassificationStoreMapped($object->getEquipBody2()->getMetadata()),
                ];
            }
        } elseif (method_exists($object, 'getMetadata') && $object->getMetadata() instanceof Classificationstore) {
            $params[uniqid()] = [
                'classificationStoreItems' => $object->getMetadata()->getItems(),
                'mappedValues' => $this->classificationStoreHelper->getClassificationStoreMapped($object->getMetadata())
            ];
        }

        return $params;
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $product
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     * @param bool $skipPreSave
     *
     * @throws \Exception
     * @return void
     */
    public function copyMetadata(AbstractObject $product, AbstractObject $object, bool $skipPreSave = false): void
    {
        $mappedParameters = $this->getMappedParameters($object);

        $mappedProperties = [];
        foreach ($mappedParameters as $objectName => $objectData) {
            foreach ($objectData['classificationStoreItems'] as $groupKeyId => $keyConfigValues) {
                $groupConfig = $this->classificationStoreService->getGroupConfigById($groupKeyId);

                foreach ($keyConfigValues as $keyConfigId => $keyConfigValue) {
                    $keyConfig = $this->classificationStoreService->getKeyConfigById($keyConfigId);

                    $ident = $groupConfig->getId() .'-'. $keyConfig->getId();
                    if (!array_key_exists($ident, $mappedProperties)) {
                        $mappedProperties[$ident] = [
                            'value' => is_object($keyConfigValue['default']) ? $keyConfigValue['default']->getValue() : $keyConfigValue['default'],
                            'groupConfig' => $groupConfig,
                            'keyConfig' => $keyConfig,
                            'language' => 'default'
                        ];
                    } else {
                        // height as SUM
                        if (in_array($objectName, ['body1', 'body2', 'center1', 'center2']) && $keyConfig->getName() === 'height') {
                            $mappedProperties[$ident]['value'] = (int)$mappedProperties[$ident]['value'] + (int)$keyConfigValue['default']->getValue();
                        }
                    }
                }
            }
        }

        $this->classificationStoreHelper->fillObjectDataOnClassificationStore($product, $mappedProperties, $skipPreSave);
    }
}
