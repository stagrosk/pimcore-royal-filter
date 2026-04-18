<?php

declare(strict_types=1);

namespace App\Pimcore\Model\DataObject;

use Pimcore\Model\DataObject\Fieldcollection;
use Pimcore\Model\DataObject\Fieldcollection\Data\Benefits;
use Pimcore\Tool;

class ProductBenefitSet extends \Pimcore\Model\DataObject\ProductBenefitSet
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

    public function getBenefitsData(): array
    {
        $benefits = $this->getBenefits();
        $languages = Tool::getValidLanguages();

        if (!$benefits instanceof Fieldcollection) {
            return [];
        }

        $result = [];
        foreach ($benefits as $item) {
            if (!$item instanceof Benefits) {
                continue;
            }

            $translations = [];
            foreach ($languages as $language) {
                $translations[$language] = [
                    'text' => $item->getTexts($language),
                ];
            }

            $result[] = [
                'type' => $item->getBenefitType(),
                'icon' => $item->getIcon(),
                'translations' => $translations,
            ];
        }

        return $result;
    }
}
