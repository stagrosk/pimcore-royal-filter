<?php

namespace App\GraphQL\Helper;

use Pimcore\Model\Asset;
use Pimcore\Model\DataObject\ContentPage;
use Pimcore\Model\DataObject\Data\BlockElement;
use Pimcore\Model\DataObject\Data\ImageGallery;
use Pimcore\Model\DataObject\Data\Link;
use Pimcore\Model\DataObject\Data\RgbaColor;
use Pimcore\Model\DataObject\Data\Video;
use Pimcore\Model\DataObject\Fieldcollection\Data\Button;
use Pimcore\Model\DataObject\Fieldcollection\Data\Headline;
use Pimcore\Model\DataObject\Fieldcollection\Data\HeroSwiper;
use Pimcore\Model\DataObject\Fieldcollection\Data\ProductGrid;
use Pimcore\Model\DataObject\Fieldcollection\Data\Image;
use Pimcore\Model\DataObject\Fieldcollection\Data\ImageContent;
use Pimcore\Model\DataObject\Fieldcollection\Data\ParalaxContent;
use Pimcore\Model\DataObject\Fieldcollection\Data\Script;
use Pimcore\Model\DataObject\Fieldcollection\Data\Text;
use Pimcore\Model\DataObject\Fieldcollection\Data\TextWithImage;
use Pimcore\Model\DataObject\Fieldcollection\Data\CategoryGrid;
use Pimcore\Model\DataObject\Fieldcollection\Data\Widget;

class ContentElementHelper
{
    private const THUMBNAIL_CONTENT = 'content-image';
    private const THUMBNAIL_GALLERY = 'content-gallery';
    private const THUMBNAIL_HERO = 'hero-swiper';
    private const THUMBNAIL_PARALAX = 'paralax-content';

    public function getElements(ContentPage|\Pimcore\Model\DataObject\BlogPost $contentPage, string $language): array
    {
        $elements = $contentPage->getElements();
        if ($elements === null) {
            return [];
        }

        $result = [];
        foreach ($elements->getItems() as $item) {
            $serialized = match (true) {
                $item instanceof Headline => $this->serializeHeadline($item, $language),
                $item instanceof Button => $this->serializeButton($item, $language),
                $item instanceof Text => $this->serializeText($item, $language),
                $item instanceof Image => $this->serializeImage($item, $language),
                $item instanceof TextWithImage => $this->serializeTextWithImage($item, $language),
                $item instanceof ImageContent => $this->serializeImageContent($item, $language),
                $item instanceof Script => $this->serializeScript($item),
                $item instanceof Widget => $this->serializeWidget($item),
                $item instanceof HeroSwiper => $this->serializeHeroSwiper($item, $language),
                $item instanceof ParalaxContent => $this->serializeParalaxContent($item, $language),
                $item instanceof ProductGrid => $this->serializeProductGrid($item, $language),
                $item instanceof CategoryGrid => $this->serializeCategoryGrid($item),
                default => null,
            };

            if ($serialized !== null) {
                $result[] = $serialized;
            }
        }

        return $result;
    }

    private function serializeHeadline(Headline $item, string $language): array
    {
        return [
            'componentType' => 'Headline',
            'headline' => [
                'headline' => $item->getHeadline($language),
                'headlineType' => $item->getHeadlineType(),
                'textBoxed' => $item->getTextBoxed(),
            ],
        ];
    }

    private function serializeButton(Button $item, string $language): array
    {
        $link = $item->getLink($language);
        $color = $item->getColor();

        return [
            'componentType' => 'Button',
            'button' => [
                'link' => $this->serializeLinkField($link),
                'color' => $color instanceof RgbaColor ? $this->rgbaToHex($color) : null,
                'isExternal' => $item->getIsExternal(),
                'position' => $item->getPosition(),
                'fullWidth' => $item->getFullWidth(),
            ],
        ];
    }

    private function serializeText(Text $item, string $language): array
    {
        return [
            'componentType' => 'Text',
            'text' => [
                'text' => $item->getText($language),
                'textBoxed' => $item->getTextBoxed(),
            ],
        ];
    }

    private function serializeImage(Image $item, string $language): array
    {
        $image = $item->getImage($language);

        return [
            'componentType' => 'Image',
            'image' => [
                'image' => $image instanceof Asset\Image ? $image->getFullPath() : null,
                'imageThumbnail' => $image instanceof Asset\Image ? $image->getThumbnail(self::THUMBNAIL_CONTENT)?->getPath() : null,
            ],
        ];
    }

    private function serializeTextWithImage(TextWithImage $item, string $language): array
    {
        $image = $item->getImage($language);

        return [
            'componentType' => 'TextWithImage',
            'textWithImage' => [
                'text' => $item->getText($language),
                'image' => $image instanceof Asset\Image ? $image->getFullPath() : null,
                'imageThumbnail' => $image instanceof Asset\Image ? $image->getThumbnail(self::THUMBNAIL_CONTENT)?->getPath() : null,
                'imagePosition' => $item->getImagePosition(),
            ],
        ];
    }

    private function serializeImageContent(ImageContent $item, string $language): array
    {
        $gallery = $item->getImageGallery($language);
        $images = [];

        if ($gallery instanceof ImageGallery) {
            foreach ($gallery->getItems() as $galleryItem) {
                $img = $galleryItem->getImage();
                if ($img instanceof Asset\Image) {
                    $images[] = [
                        'url' => $img->getFullPath(),
                        'thumbnailUrl' => $img->getThumbnail(self::THUMBNAIL_GALLERY)?->getPath(),
                    ];
                }
            }
        }

        return [
            'componentType' => 'ImageContent',
            'imageContent' => [
                'images' => $images,
            ],
        ];
    }

    private function serializeScript(Script $item): array
    {
        return [
            'componentType' => 'Script',
            'script' => [
                'scriptSrc' => $item->getScriptSrc(),
                'bodyContent' => $item->getBodyContent(),
            ],
        ];
    }

    private function serializeWidget(Widget $item): array
    {
        return [
            'componentType' => 'Widget',
            'widget' => [
                'ident' => $item->getIdent(),
            ],
        ];
    }

    private function serializeCategoryGrid(CategoryGrid $item): array
    {
        $categories = $item->getCategories();
        $ids = [];

        foreach ($categories as $category) {
            $ids[] = $category->getId();
        }

        return [
            'componentType' => 'CategoryGrid',
            'categoryGrid' => [
                'categoryIds' => $ids,
            ],
        ];
    }

    private function serializeHeroSwiper(HeroSwiper $item, string $language): array
    {
        $blockData = $item->getSlides($language);
        $slides = [];

        foreach ($blockData ?? [] as $slideBlock) {
            $slides[] = $this->serializeHeroSlide($slideBlock, $language);
        }

        return [
            'componentType' => 'HeroSwiper',
            'heroSwiper' => [
                'slides' => $slides,
            ],
        ];
    }

    /**
     * @param BlockElement[] $slideBlock
     */
    private function serializeHeroSlide(array $slideBlock, string $language): array
    {
        $asset = $this->getBlockValue($slideBlock, 'asset');
        $serializedAsset = null;

        if ($asset instanceof Asset\Image) {
            $serializedAsset = [
                'type' => 'image',
                'url' => $asset->getFullPath(),
                'thumbnailUrl' => $asset->getThumbnail(self::THUMBNAIL_HERO)?->getPath(),
            ];
        } elseif ($asset instanceof Asset\Video) {
            $serializedAsset = [
                'type' => 'video',
                'url' => $asset->getFullPath(),
                'thumbnailUrl' => null,
            ];
        }

        $primaryLink = $this->getBlockValue($slideBlock, 'primaryButtonLink');
        $primaryRelation = $this->getBlockValue($slideBlock, 'primaryButtonRelation');
        $secondaryLink = $this->getBlockValue($slideBlock, 'secondaryButtonLink');
        $secondaryRelation = $this->getBlockValue($slideBlock, 'secondaryButtonRelation');

        return [
            'title' => $this->getBlockValue($slideBlock, 'title'),
            'subtitle' => $this->getBlockValue($slideBlock, 'subtitle'),
            'text' => $this->getBlockValue($slideBlock, 'text'),
            'asset' => $serializedAsset,
            'assetText' => $this->getBlockValue($slideBlock, 'assetText'),
            'primaryButton' => ButtonHelper::serialize(
                $this->getBlockValue($slideBlock, 'primaryButtonText'),
                $primaryLink instanceof Link ? $primaryLink : null,
                $primaryRelation,
                $language,
            ),
            'secondaryButton' => ButtonHelper::serialize(
                $this->getBlockValue($slideBlock, 'secondaryButtonText'),
                $secondaryLink instanceof Link ? $secondaryLink : null,
                $secondaryRelation,
                $language,
            ),
        ];
    }

    private function serializeParalaxContent(ParalaxContent $item, string $language): array
    {
        $image = $item->getImage();
        $video = $item->getVideo();

        return [
            'componentType' => 'ParalaxContent',
            'paralaxContent' => [
                'title' => $item->getTitle($language),
                'text' => $item->getText($language),
                'button' => ButtonHelper::serialize(
                    $item->getButtonText($language),
                    $item->getButtonLink($language),
                    $item->getButtonRelation($language),
                    $language,
                ),
                'image' => $image instanceof Asset\Image ? $image->getFullPath() : null,
                'imageThumbnail' => $image instanceof Asset\Image ? $image->getThumbnail(self::THUMBNAIL_PARALAX)?->getPath() : null,
                'video' => $this->serializeVideoField($video),
                'overlay' => $item->getOverlay(),
            ],
        ];
    }

    private function serializeProductGrid(ProductGrid $item, string $language): array
    {
        $products = $item->getResolvedProducts();

        return [
            'componentType' => 'ProductGrid',
            'productGrid' => [
                'tabTitle' => $item->getTabTitle($language),
                'products' => ProductFragmentHelper::transformList($products, $language),
            ],
        ];
    }

    private function serializeVideoField(?Video $video): ?array
    {
        if (!$video instanceof Video) {
            return null;
        }

        $type = $video->getType();
        $data = $video->getData();

        if (empty($type) || empty($data)) {
            return null;
        }

        $url = $type === 'asset' && $data instanceof Asset\Video
            ? $data->getFullPath()
            : (string) $data;

        $poster = $video->getPoster();

        return [
            'type' => $type,
            'url' => $url,
            'poster' => $poster instanceof Asset\Image ? $poster->getFullPath() : null,
            'title' => $video->getTitle(),
        ];
    }

    private function serializeLinkField(?Link $link): ?array
    {
        $resolved = ButtonHelper::resolveLink($link);
        if ($resolved === null) {
            return null;
        }

        return array_merge($resolved, [
            'text' => $link->getText(),
        ]);
    }

    private function rgbaToHex(RgbaColor $color): string
    {
        return sprintf(
            '#%02x%02x%02x%02x',
            $color->getR(),
            $color->getG(),
            $color->getB(),
            $color->getA(),
        );
    }

    private function getBlockValue(array $slideBlock, string $fieldName): mixed
    {
        if (!isset($slideBlock[$fieldName])) {
            return null;
        }

        $element = $slideBlock[$fieldName];
        if ($element instanceof BlockElement) {
            return $element->getData();
        }

        return null;
    }
}
