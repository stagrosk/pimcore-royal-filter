<?php

namespace App\Shopify\Service\Translation;

use App\Shopify\Graphql\Query\Translation\TranslatableResourceQuery;
use App\Shopify\Model\Translation\TranslationInput;
use App\Shopify\Model\Translation\TranslationInputs;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\ClassDefinition;
use Pimcore\Tool;

class ShopifyTranslationMapper implements IShopifyTranslationMapper
{
    private array $keyMapper = [
        'body_html' => 'description'
    ];

    /**
     * @param \App\Shopify\Graphql\Query\Translation\TranslatableResourceQuery $translatableResourceQuery
     */
    public function __construct(
        private readonly TranslatableResourceQuery $translatableResourceQuery
    ) {
    }

    /**
     * @param \App\Shopify\Model\Translation\TranslationInputs $inputs
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @throws \Exception
     * @return \App\Shopify\Model\Translation\TranslationInputs
     */
    public function getMappedObject(TranslationInputs $inputs, AbstractObject $object): TranslationInputs
    {
        $objectLocalizedFields = $this->getObjectLocalizedFields($object);

        $translatableResourcesIndexed = $this->getShopifyTranslatableResourcesIndexed($object);
        foreach ($translatableResourcesIndexed as $translatableResource) {
            // skip EN -> that is default lang
            $translatableContentDigest = $translatableResource['digest'];
            $pimcoreKey = $this->remapKey($translatableResource['key']);

            // check if pimcoreKey is translatable on object
            if ($this->isFieldTranslatable($objectLocalizedFields, $pimcoreKey)) {
                // loop all languages and prepare translations for input
                foreach (Tool::getValidLanguages() as $locale) {
                    if ($locale === 'en') {
                        continue;
                    }

                    $inputs->addTranslationInput(new TranslationInput(
                        $translatableResource['key'],
                        $locale,
                        $object->get($pimcoreKey, $locale) ?? '',
                        $translatableContentDigest
                    ));
                }
            }
        }

        return $inputs;
    }

    /**
     * @param array $objectLocalizedFields
     * @param string $key
     *
     * @return bool
     */
    private function isFieldTranslatable(array $objectLocalizedFields, string $key): bool
    {
        $isValid = false;
        foreach ($objectLocalizedFields as $field) {
            if ($field === $key) {
                $isValid = true;
                break;
            }
        }

        return $isValid;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function remapKey(string $key): string
    {
        if (array_key_exists($key, $this->keyMapper)) {
            return $this->keyMapper[$key];
        }

        return $key;
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @throws \Exception
     * @return array
     */
    private function getObjectLocalizedFields(AbstractObject $object): array
    {
        $classId = $object->getClassId();
        $classDefinition = ClassDefinition::getById($classId);

        $localizedFields = [];
        foreach ($classDefinition->getFieldDefinitions() as $fieldDefinition) {
            if ($fieldDefinition->getName() === 'localizedfields') {
                /** @var \Pimcore\Model\DataObject\ClassDefinition\Data\Localizedfields $localizedField */
                $localizedField = $fieldDefinition;
                foreach ($localizedField->getFieldDefinitions() as $localizedFieldDefinition) {
                    $localizedFields[] = $localizedFieldDefinition->getName();
                }
            }
        }

        return $localizedFields;
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @throws \Exception
     * @return array
     */
    private function getShopifyTranslatableResourcesIndexed(AbstractObject $object): array
    {
        $translatableResourcesIndexed = [];

        // get shopify product media
        $response = $this->translatableResourceQuery->callAction($object);

        // loop all medias from shopify and check if exists on the product
        $data = $response['data']['translatableResourcesByIds'];
        if (!empty($data['userErrors'])) {
            throw new \Exception($data['userErrors'][0]['message']);
        } else {
            // loop all medias from shopify
            foreach ($data['edges'] as $edge) {
                $translatableResourcesIndexed = $edge['node']['translatableContent'];
            }
        }

        return $translatableResourcesIndexed;
    }
}
