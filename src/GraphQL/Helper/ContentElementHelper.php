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
use Pimcore\Model\DataObject\Fieldcollection\Data\Image;
use Pimcore\Model\DataObject\Fieldcollection\Data\ImageContent;
use Pimcore\Model\DataObject\Fieldcollection\Data\ParalaxContent;
use Pimcore\Model\DataObject\Fieldcollection\Data\Script;
use Pimcore\Model\DataObject\Fieldcollection\Data\Text;
use Pimcore\Model\DataObject\Fieldcollection\Data\TextWithImage;
use Pimcore\Model\DataObject\Fieldcollection\Data\Widget;

class ContentElementHelper
{
    private const THUMBNAIL_CONTENT = 'content-image';
    private const THUMBNAIL_GALLERY = 'content-gallery';
    private const THUMBNAIL_HERO = 'hero-swiper';
    private const THUMBNAIL_PARALAX = 'paralax-content';

    public static function getElements(ContentPage $contentPage, string $language): array
    {
        $elements = $contentPage->getElements();
        if ($elements === null) {
            return [];
        }

        $result = [];
        foreach ($elements->getItems() as $item) {
            $serialized = match (true) {
                $item instanceof Headline => self::serializeHeadline($item, $language),
                $item instanceof Button => self::serializeButton($item, $language),
                $item instanceof Text => self::serializeText($item, $language),
                $item instanceof Image => self::serializeImage($item, $language),
                $item instanceof TextWithImage => self::serializeTextWithImage($item, $language),
                $item instanceof ImageContent => self::serializeImageContent($item, $language),
                $item instanceof Script => self::serializeScript($item),
                $item instanceof Widget => self::serializeWidget($item),
                $item instanceof HeroSwiper => self::serializeHeroSwiper($item, $language),
                $item instanceof ParalaxContent => self::serializeParalaxContent($item, $language),
                default => null,
            };

            if ($serialized !== null) {
                $result[] = $serialized;
            }
        }

        return $result;
    }

    private static function serializeHeadline(Headline $item, string $language): array
    {
        return [
            'componentType' => 'Headline',
            'headline' => $item->getHeadline($language),
            'headlineType' => $item->getHeadlineType(),
            'textBoxed' => $item->getTextBoxed(),
        ];
    }

    private static function serializeButton(Button $item, string $language): array
    {
        $link = $item->getLink($language);
        $color = $item->getColor();

        return [
            'componentType' => 'Button',
            'link' => self::serializeLinkField($link),
            'color' => $color instanceof RgbaColor ? self::rgbaToHex($color) : null,
            'isExternal' => $item->getIsExternal(),
            'position' => $item->getPosition(),
            'fullWidth' => $item->getFullWidth(),
        ];
    }

    private static function serializeText(Text $item, string $language): array
    {
        return [
            'componentType' => 'Text',
            'text' => $item->getText($language),
            'textBoxed' => $item->getTextBoxed(),
        ];
    }

    private static function serializeImage(Image $item, string $language): array
    {
        $image = $item->getImage($language);

        return [
            'componentType' => 'Image',
            'image' => $image instanceof Asset\Image ? $image->getFullPath() : null,
            'imageThumbnail' => $image instanceof Asset\Image ? $image->getThumbnail(self::THUMBNAIL_CONTENT)?->getPath() : null,
        ];
    }

    private static function serializeTextWithImage(TextWithImage $item, string $language): array
    {
        $image = $item->getImage($language);

        return [
            'componentType' => 'TextWithImage',
            'text' => $item->getText($language),
            'image' => $image instanceof Asset\Image ? $image->getFullPath() : null,
            'imageThumbnail' => $image instanceof Asset\Image ? $image->getThumbnail(self::THUMBNAIL_CONTENT)?->getPath() : null,
            'imagePosition' => $item->getImagePosition(),
        ];
    }

    private static function serializeImageContent(ImageContent $item, string $language): array
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
            'images' => $images,
        ];
    }

    private static function serializeScript(Script $item): array
    {
        return [
            'componentType' => 'Script',
            'scriptSrc' => $item->getScriptSrc(),
            'bodyContent' => $item->getBodyContent(),
        ];
    }

    private static function serializeWidget(Widget $item): array
    {
        return [
            'componentType' => 'Widget',
            'ident' => $item->getIdent(),
        ];
    }

    private static function serializeHeroSwiper(HeroSwiper $item, string $language): array
    {
        $blockData = $item->getSlides($language);
        $slides = [];

        foreach ($blockData ?? [] as $slideBlock) {
            $slides[] = self::serializeHeroSlide($slideBlock, $language);
        }

        return [
            'componentType' => 'HeroSwiper',
            'heroSlides' => $slides,
        ];
    }

    /**
     * @param BlockElement[] $slideBlock
     */
    private static function serializeHeroSlide(array $slideBlock, string $language): array
    {
        $asset = self::getBlockValue($slideBlock, 'asset');
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

        $primaryLink = self::getBlockValue($slideBlock, 'primaryButtonLink');
        $primaryRelation = self::getBlockValue($slideBlock, 'primaryButtonRelation');
        $secondaryLink = self::getBlockValue($slideBlock, 'secondaryButtonLink');
        $secondaryRelation = self::getBlockValue($slideBlock, 'secondaryButtonRelation');

        return [
            'title' => self::getBlockValue($slideBlock, 'title'),
            'subtitle' => self::getBlockValue($slideBlock, 'subtitle'),
            'text' => self::getBlockValue($slideBlock, 'text'),
            'asset' => $serializedAsset,
            'assetText' => self::getBlockValue($slideBlock, 'assetText'),
            'primaryButton' => ButtonHelper::serialize(
                self::getBlockValue($slideBlock, 'primaryButtonText'),
                $primaryLink instanceof Link ? $primaryLink : null,
                $primaryRelation,
                $language,
            ),
            'secondaryButton' => ButtonHelper::serialize(
                self::getBlockValue($slideBlock, 'secondaryButtonText'),
                $secondaryLink instanceof Link ? $secondaryLink : null,
                $secondaryRelation,
                $language,
            ),
        ];
    }

    private static function serializeParalaxContent(ParalaxContent $item, string $language): array
    {
        $image = $item->getImage();
        $video = $item->getVideo();

        return [
            'componentType' => 'ParalaxContent',
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
            'video' => self::serializeVideoField($video),
            'overlay' => $item->getOverlay(),
        ];
    }

    private static function serializeVideoField(?Video $video): ?array
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

    private static function serializeLinkField(?Link $link): ?array
    {
        $resolved = ButtonHelper::resolveLink($link);
        if ($resolved === null) {
            return null;
        }

        return array_merge($resolved, [
            'text' => $link->getText(),
        ]);
    }

    private static function rgbaToHex(RgbaColor $color): string
    {
        return sprintf(
            '#%02x%02x%02x%02x',
            $color->getR(),
            $color->getG(),
            $color->getB(),
            $color->getA(),
        );
    }

    private static function getBlockValue(array $slideBlock, string $fieldName): mixed
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
