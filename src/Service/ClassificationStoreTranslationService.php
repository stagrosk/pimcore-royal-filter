<?php

declare(strict_types=1);

namespace App\Service;

use Pimcore\Model\Translation;
use Pimcore\Tool;

class ClassificationStoreTranslationService
{
    public const PREFIX_GROUP = 'cs_group_';
    public const PREFIX_KEY = 'cs_key_';
    public const TRANSLATION_DOMAIN = 'messages';

    /**
     * Get translations for a classification store group
     *
     * @param string $groupName
     * @param string $defaultTitle English title as fallback
     *
     * @return array Translations indexed by language
     */
    public function getGroupTranslations(string $groupName, string $defaultTitle): array
    {
        $key = self::PREFIX_GROUP . $groupName;
        return $this->getTranslations($key, $defaultTitle);
    }

    /**
     * Get translations for a classification store key
     *
     * @param string $keyName
     * @param string $defaultTitle English title as fallback
     *
     * @return array Translations indexed by language
     */
    public function getKeyTranslations(string $keyName, string $defaultTitle): array
    {
        $key = self::PREFIX_KEY . $keyName;
        return $this->getTranslations($key, $defaultTitle);
    }

    /**
     * Get or create translations for a key
     *
     * @param string $translationKey
     * @param string $defaultValue English value as fallback
     *
     * @return array Translations indexed by language
     */
    private function getTranslations(string $translationKey, string $defaultValue): array
    {
        $languages = Tool::getValidLanguages();
        $translations = [];

        // Try to get existing translation
        $translation = Translation::getByKey($translationKey, self::TRANSLATION_DOMAIN);

        // If translation doesn't exist, create it with default English value
        if (!$translation instanceof Translation) {
            $this->createTranslation($translationKey, $defaultValue, $languages);
            $translation = Translation::getByKey($translationKey, self::TRANSLATION_DOMAIN);
        }

        // Build translations array
        foreach ($languages as $language) {
            $translatedValue = $translation?->getTranslation($language);

            // If no translation for this language, use default
            if (empty($translatedValue)) {
                $translatedValue = $defaultValue;
            }

            $translations[$language] = [
                'name' => $translatedValue,
            ];
        }

        return $translations;
    }

    /**
     * Create a new translation entry
     *
     * @param string $key
     * @param string $defaultValue
     * @param array $languages
     *
     * @return void
     */
    private function createTranslation(string $key, string $defaultValue, array $languages): void
    {
        $translation = new Translation();
        $translation->setKey($key);
        $translation->setDomain(self::TRANSLATION_DOMAIN);

        // Set default value for all languages initially
        foreach ($languages as $language) {
            $translation->addTranslation($language, $defaultValue);
        }

        // Mark English as the "source" translation
        if (in_array('en', $languages)) {
            $translation->addTranslation('en', $defaultValue);
        }

        $translation->save();
    }

    /**
     * Get all untranslated classification store keys
     * (where all languages have the same value as English)
     *
     * @return array
     */
    public function getUntranslatedKeys(): array
    {
        $untranslated = [];
        $languages = Tool::getValidLanguages();

        $listing = new Translation\Listing();
        $listing->setDomain(self::TRANSLATION_DOMAIN);
        $listing->addConditionParam('`key` LIKE ?', self::PREFIX_GROUP . '%');
        $listing->addConditionParam('`key` LIKE ?', self::PREFIX_KEY . '%', 'OR');

        foreach ($listing as $translation) {
            $englishValue = $translation->getTranslation('en');
            $needsTranslation = false;

            foreach ($languages as $language) {
                if ($language === 'en') {
                    continue;
                }

                $value = $translation->getTranslation($language);
                // If value is empty or same as English, it needs translation
                if (empty($value) || $value === $englishValue) {
                    $needsTranslation = true;
                    break;
                }
            }

            if ($needsTranslation) {
                $untranslated[] = [
                    'key' => $translation->getKey(),
                    'englishValue' => $englishValue,
                ];
            }
        }

        return $untranslated;
    }
}