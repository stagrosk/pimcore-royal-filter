<?php

namespace App\Pimcore\Model\DataObject;

use App\Pimcore\DataObject\Calculator\ParametersConfigCalculator;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Data\ImageGallery;
use Pimcore\Model\DataObject\Fieldcollection;
use Pimcore\Tool;
use PimcoreHeadlessContentBundle\Model\SlugAwareInterface;

class Product extends \Pimcore\Model\DataObject\Product implements SlugAwareInterface
{
    /**
     * @param string|null $language
     *
     * @return string|null
     */
    public function getSlugValue(?string $language = null): ?string
    {
        return $this->getTitle($language);
    }

    /**
     * Get translations for all languages
     *
     * @return array
     */
    public function getTranslations(): array
    {
        $translations = [];
        $languages = Tool::getValidLanguages();

        foreach ($languages as $language) {
            $translations[$language] = [
                'title' => $this->getTitle($language),
                'shortDescription' => $this->getShortDescription($language),
                'description' => $this->getDescription($language),
                'seoTitle' => $this->getSeoTitle($language),
                'seoDescription' => $this->getSeoDescription($language),
                'slug' => $this->getSlug($language),
                'handle' => $this->getHandle($language),
            ];
        }

        return $translations;
    }

    /**
     * Get prices data for serialization
     *
     * @return array
     */
    public function getPricesData(): array
    {
        $prices = [];
        $pricesCollection = $this->getPrices();

        if ($pricesCollection instanceof Fieldcollection) {
            foreach ($pricesCollection as $priceItem) {
                $priceList = method_exists($priceItem, 'getPriceList') ? $priceItem->getPriceList() : null;

                $prices[] = [
                    'priceListId' => $priceList?->getId(),
                    'price' => method_exists($priceItem, 'getPrice') ? $priceItem->getPrice() : null,
                    'compareAtPrice' => method_exists($priceItem, 'getCompareAtPrice') ? $priceItem->getCompareAtPrice() : null,
                    'wholesalePrice' => method_exists($priceItem, 'getWholesalePrice') ? $priceItem->getWholesalePrice() : null,
                    'wholesaleSupplierPrice' => method_exists($priceItem, 'getWholesaleSupplierPrice') ? $priceItem->getWholesaleSupplierPrice() : null,
                    'unitPrice' => method_exists($priceItem, 'getUnitPrice') ? $priceItem->getUnitPrice() : null,
                    'priceWithTax' => method_exists($priceItem, 'getPricesWithVat') ? (bool) $priceItem->getPricesWithVat() : false,
                ];
            }
        }

        return $prices;
    }

    /**
     * Get images data for serialization
     *
     * @return array
     */
    public function getImagesData(): array
    {
        $images = [];
        $imageGallery = $this->getImageGallery();

        if ($imageGallery instanceof ImageGallery) {
            foreach ($imageGallery->getItems() as $hotspotImage) {
                $asset = $hotspotImage->getImage();
                if ($asset) {
                    $images[] = [
                        'id' => $asset->getId(),
                        'filename' => $asset->getFilename(),
                        'path' => $asset->getFullPath(),
                        'mimeType' => $asset->getMimeType(),
                    ];
                }
            }
        }

        return $images;
    }

    /**
     * Get collections data for serialization (only IDs for import)
     *
     * @return array
     */
    public function getCollectionsData(): array
    {
        $collectionIds = [];

        foreach ($this->getCollections() as $collection) {
            if ($collection->isPublished()) {
                $collectionIds[] = $collection->getId();
            }
        }

        return $collectionIds;
    }

    /**
     * Get manufacturer data for serialization
     *
     * @return array|null
     */
    public function getManufacturerData(): ?array
    {
        $manufacturer = $this->getManufacturer();

        if (!$manufacturer) {
            return null;
        }

        $logo = $manufacturer->getLogo();

        return [
            'id' => $manufacturer->getId(),
            'title' => $manufacturer->getTitle(),
            'description' => $manufacturer->getDescription(),
            'logo' => $logo ? [
                'id' => $logo->getId(),
                'filename' => $logo->getFilename(),
                'path' => $logo->getFullPath(),
                'mimeType' => $logo->getMimeType(),
            ] : null,
        ];
    }

    /**
     * Get parameters config data for serialization (uses calculator)
     *
     * @return array
     */
    public function getParametersConfigData(): array
    {
        $calculator = new ParametersConfigCalculator();

        return $calculator->getParametersConfig($this, false);
    }

    /**
     * Get variant options data for serialization
     *
     * @return array
     */
    public function getVariantOptionsData(): array
    {
        $options = [];
        $variantOptions = $this->getVariantOptions();

        if ($variantOptions instanceof Fieldcollection) {
            foreach ($variantOptions as $option) {
                $options[] = [
                    'type' => $option->getType(),
                    'name' => method_exists($option, 'getName') ? $option->getName() : null,
                    'value' => method_exists($option, 'getValue') ? $option->getValue() : null,
                ];
            }
        }

        return $options;
    }

    /**
     * Get variants (children products) IDs for serialization
     *
     * @return array
     */
    public function getVariantsData(): array
    {
        $variantIds = [];
        $children = $this->getChildren([AbstractObject::OBJECT_TYPE_VARIANT, AbstractObject::OBJECT_TYPE_OBJECT]);

        foreach ($children as $child) {
            if ($child instanceof self && $child->isPublished()) {
                $variantIds[] = $child->getId();
            }
        }

        return $variantIds;
    }

    /**
     * Get master product ID if this is a variant
     *
     * @return int|null
     */
    public function getMasterId(): ?int
    {
        $parent = $this->getParent();

        if ($parent instanceof self) {
            return $parent->getId();
        }

        return null;
    }

    /**
     * Get product options data for serialization
     *
     * @return array
     */
    public function getFlagsData(): array
    {
        $flags = $this->getFlags();

        if (empty($flags)) {
            return [];
        }

        $ids = [];
        foreach ($flags as $flag) {
            if ($flag instanceof \Pimcore\Model\DataObject\ProductFlag && $flag->isPublished()) {
                $ids[] = $flag->getId();
            }
        }

        return $ids;
    }

    public function getProductOptionsData(): array
    {
        $options = [];
        $productOptions = $this->getProductOptions();
        $languages = Tool::getValidLanguages();

        if ($productOptions instanceof Fieldcollection) {
            foreach ($productOptions as $option) {
                $optionGroup = method_exists($option, 'getProductOptionGroup') ? $option->getProductOptionGroup() : null;
                $optionValue = method_exists($option, 'getProductOption') ? $option->getProductOption() : null;

                if ($optionGroup && $optionValue) {
                    // Get translations for group
                    $groupTranslations = [];
                    foreach ($languages as $language) {
                        $groupTranslations[$language] = [
                            'name' => $optionGroup->getName($language),
                        ];
                    }

                    // Get translations for option
                    $optionTranslations = [];
                    foreach ($languages as $language) {
                        $optionTranslations[$language] = [
                            'name' => $optionValue->getName($language),
                        ];
                    }

                    $options[] = [
                        'group' => [
                            'id' => $optionGroup->getId(),
                            'code' => $optionGroup->getCode(),
                            'translations' => $groupTranslations,
                        ],
                        'option' => [
                            'id' => $optionValue->getId(),
                            'code' => $optionValue->getCode(),
                            'translations' => $optionTranslations,
                        ],
                    ];
                }
            }
        }

        return $options;
    }
}
