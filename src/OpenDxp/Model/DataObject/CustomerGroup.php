<?php

declare(strict_types=1);

namespace App\OpenDxp\Model\DataObject;

use OpenDxp\Tool;

class CustomerGroup extends \OpenDxp\Model\DataObject\CustomerGroup
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
