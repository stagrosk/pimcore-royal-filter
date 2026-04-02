<?php

namespace App\Pimcore\Model\DataObject;

use App\Pimcore\ClassificationStore\ClassificationStoreHelper;
use App\Pimcore\Model\ClassificationStore\ClassificationStoreMappingItem;
use App\Service\ClassificationStoreTranslationService;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Data\RgbaColor;
use Pimcore\Tool;
use PimcoreHeadlessContentBundle\Model\NavigationAwareInterface;
use PimcoreHeadlessContentBundle\Model\SlugAwareInterface;

class Collection extends \Pimcore\Model\DataObject\Collection implements SlugAwareInterface, NavigationAwareInterface
{
    private ?ClassificationStoreTranslationService $translationService = null;

    /**
     * @return ClassificationStoreTranslationService
     */
    private function getTranslationService(): ClassificationStoreTranslationService
    {
        if ($this->translationService === null) {
            $this->translationService = \Pimcore::getContainer()->get(ClassificationStoreTranslationService::class);
        }

        return $this->translationService;
    }

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
            'thumbnails' => [
                'icon' => $image->getThumbnail('category-icon')?->getPath(),
                'tile' => $image->getThumbnail('category-tile')?->getPath(),
                'preview' => $image->getThumbnail('category-preview')?->getPath(),
            ],
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
        $translationService = $this->getTranslationService();

        // Get translations from Pimcore shared translations (auto-creates if missing)
        // Note: GroupConfig doesn't have getTitle(), use getDescription() instead
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

    public function getTextColorHex(): ?string
    {
        $color = $this->getTextColor();

        return $color instanceof RgbaColor
            ? sprintf('#%02x%02x%02x', $color->getR(), $color->getG(), $color->getB())
            : null;
    }

    public function getBackgroundColorHex(): ?string
    {
        $color = $this->getBackgroundColor();

        return $color instanceof RgbaColor
            ? sprintf('#%02x%02x%02x', $color->getR(), $color->getG(), $color->getB())
            : null;
    }

    public function getParentCollectionId(): ?int
    {
        $parent = $this->getParent();

        if ($parent instanceof self) {
            return $parent->getId();
        }

        return null;
    }

    /**
     * Get children Collection IDs
     */
    public function getChildrenIds(): array
    {
        $childrenIds = [];
        $children = $this->getChildren([AbstractObject::OBJECT_TYPE_OBJECT]);

        foreach ($children as $child) {
            if ($child instanceof self && $child->isPublished()) {
                $childrenIds[] = $child->getId();
            }
        }

        return $childrenIds;
    }
}
