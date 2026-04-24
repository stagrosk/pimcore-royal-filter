<?php

declare(strict_types=1);

namespace App\OpenDxp\Model\DataObject;

use App\OpenDxp\ClassificationStore\ClassificationStoreHelper;
use App\OpenDxp\Model\ClassificationStore\ClassificationStoreMappingItem;
use App\Service\ClassificationStoreTranslationService;
use OpenDxp\Model\DataObject\Data\ImageGallery;
use OpenDxp\Model\DataObject\Fieldcollection;
use OpenDxp\Tool;

class Whirlpool extends \OpenDxp\Model\DataObject\Whirlpool
{
    private ?ClassificationStoreTranslationService $translationService = null;

    private function getTranslationService(): ClassificationStoreTranslationService
    {
        if ($this->translationService === null) {
            $this->translationService = \OpenDxp::getContainer()->get(ClassificationStoreTranslationService::class);
        }

        return $this->translationService;
    }

    public function getTranslations(): array
    {
        $translations = [];
        $languages = Tool::getValidLanguages();

        foreach ($languages as $language) {
            $translations[$language] = [
                'title' => $this->getTitle($language),
                'shortDescription' => $this->getShortDescription($language),
                'description' => $this->getDescription($language),
            ];
        }

        return $translations;
    }

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
            'logo' => $logo ? [
                'id' => $logo->getId(),
                'filename' => $logo->getFilename(),
                'path' => $logo->getFullPath(),
                'mimeType' => $logo->getMimeType(),
            ] : null,
        ];
    }

    public function getDefaultImageData(): ?array
    {
        $image = $this->getDefaultImage();

        if (!$image) {
            return null;
        }

        return [
            'id' => $image->getId(),
            'filename' => $image->getFilename(),
            'path' => $image->getFullPath(),
            'mimeType' => $image->getMimeType(),
        ];
    }

    public function getImagesData(): array
    {
        $images = [];
        $imageGallery = $this->getImages();

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

    public function getCustomerImagesData(): array
    {
        $images = [];
        $imageGallery = $this->getCustomerImages();

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

    public function getProductApiId(): ?string
    {
        $product = $this->getProduct();

        if (!$product instanceof \OpenDxp\Model\DataObject\Product) {
            return null;
        }

        return $product->getApiId();
    }

    public function getCollectionId(): ?int
    {
        $collection = $this->getCollection();

        return $collection?->getId();
    }

    public function getPaperCartridgesData(): array
    {
        $cartridges = $this->getPaperCartridges();

        if (empty($cartridges)) {
            return [];
        }

        $result = [];
        foreach ($cartridges as $cartridge) {
            if (!$cartridge->isPublished()) {
                continue;
            }

            $codes = [];
            $codesCollection = $cartridge->getCodes();
            if ($codesCollection instanceof Fieldcollection) {
                foreach ($codesCollection as $codeItem) {
                    $codes[] = [
                        'code' => method_exists($codeItem, 'getCode') ? $codeItem->getCode() : null,
                        'showInTitle' => method_exists($codeItem, 'getShowInTitle') ? $codeItem->getShowInTitle() : false,
                    ];
                }
            }

            $languages = Tool::getValidLanguages();
            $translations = [];
            foreach ($languages as $language) {
                $translations[$language] = [
                    'title' => $cartridge->getTitle($language),
                ];
            }

            $result[] = [
                'id' => $cartridge->getId(),
                'translations' => $translations,
                'codes' => $codes,
            ];
        }

        return $result;
    }

    public function getParametersConfigData(): array
    {
        $classificationStoreHelper = new ClassificationStoreHelper();
        $mapping = $classificationStoreHelper->getClassificationStoreMapped($this->getMetadata());
        $items = $mapping->getClassificationStoreMappingItems();

        $parameters = [];
        foreach ($items as $item) {
            $parameters[] = $this->mapItem($item);
        }

        return $parameters;
    }

    private function mapItem(ClassificationStoreMappingItem $item): array
    {
        $keyConfig = $item->getKeyConfig();
        $groupConfig = $item->getGroupConfig();
        $translationService = $this->getTranslationService();

        $groupTranslations = $translationService->getGroupTranslations(
            $groupConfig->getName(),
            $groupConfig->getDescription() ?: $groupConfig->getName()
        );

        $keyTranslations = $translationService->getKeyTranslations(
            $keyConfig->getName(),
            $keyConfig->getTitle() ?: $keyConfig->getName()
        );

        return [
            'group' => $groupConfig->getName(),
            'groupId' => $groupConfig->getId(),
            'groupTranslations' => $groupTranslations,
            'key' => $keyConfig->getName(),
            'keyId' => $keyConfig->getId(),
            'keyTranslations' => $keyTranslations,
            'label' => $item->getLabel(),
            'type' => $keyConfig->getType(),
            'value' => $item->getValue(),
            'rawValue' => $item->getRawValue(),
            'unit' => $item->getUnit(),
            'unitLongName' => $item->getUnitLongName(),
            'optionValue' => $item->getOptionValue(),
        ];
    }
}
