<?php

namespace App\Pimcore\Model\DataObject;

use App\Pimcore\ClassificationStore\ClassificationStoreHelper;
use App\Pimcore\Model\ClassificationStore\ClassificationStoreMappingItem;
use Pimcore\Tool;
use PimcoreHeadlessContentBundle\Model\NavigationAwareInterface;
use PimcoreHeadlessContentBundle\Model\SlugAwareInterface;

class Collection extends \Pimcore\Model\DataObject\Collection implements SlugAwareInterface, NavigationAwareInterface
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
     * @param string|null $language
     *
     * @return string|null
     */
    public function getNavigationTitle(?string $language = null): ?string
    {
        return $this->getTitle($language);
    }

    /**
     * @param string|null $language
     *
     * @return array
     */
    public function getNavigationAdditionalData(?string $language = null): array
    {
        return [];
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
                'description' => $this->getDescription($language),
                'slug' => $this->getSlug($language),
                'handle' => $this->getHandle($language),
            ];
        }

        return $translations;
    }

    /**
     * Get image data for serialization
     *
     * @return array|null
     */
    public function getImageData(): ?array
    {
        $image = $this->getImage();

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

    /**
     * Get parameters config data for serialization
     *
     * @return array
     */
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

    /**
     * @param ClassificationStoreMappingItem $item
     *
     * @return array
     */
    private function mapItem(ClassificationStoreMappingItem $item): array
    {
        $keyConfig = $item->getKeyConfig();
        $groupConfig = $item->getGroupConfig();

        return [
            'group' => $groupConfig->getName(),
            'groupId' => $groupConfig->getId(),
            'key' => $keyConfig->getName(),
            'keyId' => $keyConfig->getId(),
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
