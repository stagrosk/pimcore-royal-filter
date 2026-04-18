<?php

declare(strict_types=1);

namespace App\Pimcore\Model\DataObject;

use Pimcore\Tool;

class CustomerGroup extends \Pimcore\Model\DataObject\CustomerGroup
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
}
