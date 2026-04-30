<?php

namespace App\OpenDxp\Model\DataObject;

use App\OpenDxp\DataObject\Calculator\ParametersConfigCalculator;
use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\Data\ImageGallery;
use OpenDxp\Model\DataObject\Fieldcollection;
use OpenDxp\Tool;
use OpendxpHeadlessContentBundle\Model\SlugAwareInterface;

class Product extends \OpenDxp\Model\DataObject\Product implements SlugAwareInterface
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
                    'purchasePrice' => method_exists($priceItem, 'getPurchasePrice') ? $priceItem->getPurchasePrice() : null,
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
     * Get downloads data for serialization (asset relation -> file metadata).
     *
     * @return array
     */
    public function getDownloadsData(): array
    {
        return $this->serializeAssetRelation($this->getDownloads());
    }

    /**
     * Get manuals data for serialization (asset relation -> file metadata).
     *
     * @return array
     */
    public function getManualsData(): array
    {
        return $this->serializeAssetRelation($this->getManuals());
    }

    /**
     * Friendly extension labels for the most common mime types we ship in
     * downloads/manuals. Anything not mapped falls back to the filename
     * extension (or null).
     */
    private const MIME_TYPE_EXTENSIONS = [
        'image/svg+xml' => 'svg',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
        'application/pdf' => 'pdf',
        'application/zip' => 'zip',
        'application/x-zip-compressed' => 'zip',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        'application/msword' => 'doc',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.ms-powerpoint' => 'ppt',
        'text/calendar' => 'ics',
        'text/csv' => 'csv',
        'text/plain' => 'txt',
        'video/mp4' => 'mp4',
        'audio/mpeg' => 'mp3',
    ];

    /**
     * Serialize an array of assets into the format the storefront consumes.
     * Reads optional `title` property on the asset for the display name; falls
     * back to the asset filename. URL is intentionally a relative path - the
     * storefront resolves the absolute href on its side.
     *
     * @param array<int, mixed> $assets
     *
     * @return array<int, array{id: int, title: string, filename: string, extension: string|null, mimeType: string|null, url: string}>
     */
    private function serializeAssetRelation(array $assets): array
    {
        $items = [];
        foreach ($assets as $asset) {
            if (!$asset instanceof \OpenDxp\Model\Asset) {
                continue;
            }

            $title = $asset->getProperty('title');
            if (!is_string($title) || $title === '') {
                $title = $asset->getFilename();
            }

            $items[] = [
                'id' => $asset->getId(),
                'title' => $title,
                'filename' => $asset->getFilename(),
                'extension' => $this->resolveExtension($asset),
                'mimeType' => $asset->getMimeType(),
                'url' => $asset->getFullPath(),
            ];
        }

        return $items;
    }

    private function resolveExtension(\OpenDxp\Model\Asset $asset): ?string
    {
        $mime = $asset->getMimeType();
        if (is_string($mime) && isset(self::MIME_TYPE_EXTENSIONS[$mime])) {
            return self::MIME_TYPE_EXTENSIONS[$mime];
        }

        $ext = pathinfo((string) $asset->getFilename(), PATHINFO_EXTENSION);

        return $ext !== '' ? strtolower($ext) : null;
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
            if ($flag instanceof \OpenDxp\Model\DataObject\ProductFlag && $flag->isPublished()) {
                $ids[] = $flag->getId();
            }
        }

        return $ids;
    }

    public function getCrossSellingProductsData(): array
    {
        $ids = [];
        foreach ($this->getCrossSellingProducts() as $product) {
            if ($product instanceof \OpenDxp\Model\DataObject\Product && $product->isPublished()) {
                $ids[] = $product->getId();
            }
        }

        return $ids;
    }

    public function getSimularProductsData(): array
    {
        $ids = [];
        foreach ($this->getSimularProducts() as $product) {
            if ($product instanceof \OpenDxp\Model\DataObject\Product && $product->isPublished()) {
                $ids[] = $product->getId();
            }
        }

        return $ids;
    }

    public function getCustomerGroupsData(): array
    {
        return \App\OpenDxp\Helpers\CustomerGroupHelper::getPublishedIds($this->getCustomerGroups());
    }

    public function getBenefitSetId(): ?int
    {
        $set = $this->getBenefictSet();

        return $set?->getId();
    }

    public function getPaperCartridgesData(): array
    {
        $cartridges = $this->getPaperCartridges();

        if (empty($cartridges)) {
            return [];
        }

        $ids = [];
        foreach ($cartridges as $cartridge) {
            if ($cartridge->isPublished()) {
                $ids[] = $cartridge->getId();
            }
        }

        return $ids;
    }

    public function getProductTabsData(): array
    {
        $tabs = [];
        $productTabs = $this->getProductTabs();
        $languages = Tool::getValidLanguages();

        if ($productTabs instanceof Fieldcollection) {
            foreach ($productTabs as $tab) {
                $translations = [];
                foreach ($languages as $language) {
                    $translations[$language] = [
                        'tabTitle' => method_exists($tab, 'getTabTitle') ? $tab->getTabTitle($language) : null,
                        'tabData' => method_exists($tab, 'getTabData') ? $tab->getTabData($language) : null,
                    ];
                }

                $tabs[] = [
                    'icon' => method_exists($tab, 'getIcon') ? $tab->getIcon() : null,
                    'translations' => $translations,
                ];
            }
        }

        return $tabs;
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
