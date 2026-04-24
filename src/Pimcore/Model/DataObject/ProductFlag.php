<?php

declare(strict_types=1);

namespace App\Pimcore\Model\DataObject;

use OpenDxp\Model\DataObject\Data\RgbaColor;
use OpenDxp\Tool;

class ProductFlag extends \Pimcore\Model\DataObject\ProductFlag
{
    public function getTranslations(): array
    {
        $translations = [];
        $languages = Tool::getValidLanguages();

        foreach ($languages as $language) {
            $translations[$language] = [
                'title' => $this->getTitle($language),
            ];
        }

        return $translations;
    }

    public function getColorHex(): ?string
    {
        $color = $this->getColor();

        if (!$color instanceof RgbaColor) {
            return null;
        }

        return sprintf('#%02x%02x%02x', $color->getR(), $color->getG(), $color->getB());
    }
}
