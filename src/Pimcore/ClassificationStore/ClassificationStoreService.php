<?php

namespace App\Pimcore\ClassificationStore;

use Cocur\Slugify\Slugify;
use Pimcore\Model\DataObject\ClassDefinition\Data\Checkbox;
use Pimcore\Model\DataObject\ClassDefinition\Data\QuantityValue;
use Pimcore\Model\DataObject\ClassDefinition\Data\Numeric;
use Pimcore\Model\DataObject\ClassDefinition\Data\Select;
use Pimcore\Model\DataObject\ClassDefinition\Data\Date;
use Pimcore\Model\DataObject\ClassDefinition\Data\Input;
use Pimcore\Model\DataObject\ClassDefinition\Data\TypeDeclarationSupportInterface;
use Pimcore\Model\DataObject\Classificationstore\CollectionConfig;
use Pimcore\Model\DataObject\Classificationstore\CollectionGroupRelation;
use Pimcore\Model\DataObject\Classificationstore\GroupConfig;
use Pimcore\Model\DataObject\Classificationstore\KeyConfig;
use Pimcore\Model\DataObject\Classificationstore\KeyGroupRelation;

class ClassificationStoreService
{
    public const RANGE_VALUE_SUFFIX_FROM = 'From';
    public const RANGE_VALUE_SUFFIX_TO = 'To';

    /**
     * @param string $code
     * @param string $title
     * @param string $unit
     *
     * @return \Pimcore\Model\DataObject\ClassDefinition\Data\QuantityValue[]
     */
    public function createQuantityValueInput(string $code, string $title, string $unit): array
    {
        $slugify = new Slugify();
        $codeSlugged = $slugify->slugify($code, ['lowercase']);

        $quantityValue = new QuantityValue();
        $quantityValue->setName($codeSlugged);
        $quantityValue->setTitle($title);
        $quantityValue->setDefaultUnit($unit);
        $quantityValue->setValidUnits([$unit]);

        return [$quantityValue];
    }

    /**
     * @param string $code
     * @param string $title
     *
     * @return array
     */
    public function createInputInput(string $code, string $title): array
    {
        $slugify = new Slugify();
        $codeSlugged = $slugify->slugify($code, ['lowercase']);

        $input = new Input();
        $input->setName($codeSlugged);
        $input->setTitle($title);

        return [$input];
    }

    /**
     * @param string $code
     * @param string $title
     *
     * @return \Pimcore\Model\DataObject\ClassDefinition\Data\Numeric[]
     */
    public function createNumericInput(string $code, string $title): array
    {
        $slugify = new Slugify();
        $codeSlugged = $slugify->slugify($code, ['lowercase']);

        $numeric = new Numeric();
        $numeric->setName($codeSlugged);
        $numeric->setTitle($title);

        return [$numeric];
    }

    /**
     * @param string $code
     * @param string $title
     *
     * @return \Pimcore\Model\DataObject\ClassDefinition\Data\Date[]
     */
    public function createDateInput(string $code, string $title): array
    {
        $slugify = new Slugify();
        $codeSlugged = $slugify->slugify($code, ['lowercase']);

        $numeric = new Date();
        $numeric->setName($codeSlugged);
        $numeric->setTitle($title);

        return [$numeric];
    }

    /**
     * @param string $code
     * @param string $title
     * @param array $options
     *
     * @return \Pimcore\Model\DataObject\ClassDefinition\Data\Select[]
     */
    public function createSelectValueInput(string $code, string $title, array $options): array
    {
        $slugify = new Slugify();
        $codeSlugged = $slugify->slugify($code, ['lowercase']);

        $selectValue = new Select();
        $selectValue->setName($codeSlugged);
        $selectValue->setTitle($title);
        $selectValue->setOptions($options);

        return [$selectValue];
    }

    /**
     * @param string $code
     * @param string $title
     *
     * @return \Pimcore\Model\DataObject\ClassDefinition\Data\Checkbox[]
     */
    public function createCheckboxValueInput(string $code, string $title): array
    {
        $slugify = new Slugify();
        $codeSlugged = $slugify->slugify($code, ['lowercase']);

        $checkboxValue = new Checkbox();
        $checkboxValue->setName($codeSlugged);
        $checkboxValue->setTitle($title);
        $checkboxValue->setDefaultValue(false);

        return [$checkboxValue];
    }

    /**
     * @param string $code
     * @param string $title
     * @param string $unit
     * @return array
     */
    public function createRangeQuantityValueInput(string $code, string $title, string $unit): array
    {
        $fromField = $this->createQuantityValueInput($code . self::RANGE_VALUE_SUFFIX_FROM, $title . ' (von)', $unit);
        $toField = $this->createQuantityValueInput($code . self::RANGE_VALUE_SUFFIX_TO, $title . ' (bis)', $unit);

        return [
            'from' => reset($fromField),
            'to' => reset($toField),
        ];
    }

    /**
     * @param string $code
     * @param string $title
     *
     * @return array
     */
    public function createRangeNumericInput(string $code, string $title): array
    {
        $fromField = $this->createNumericInput($code . self::RANGE_VALUE_SUFFIX_FROM, $title . ' (von)');
        $toField = $this->createNumericInput($code . self::RANGE_VALUE_SUFFIX_TO, $title . ' (bis)');

        return [
            'from' => reset($fromField),
            'to' => reset($toField),
        ];
    }

    /**
     * @param int $id
     *
     * @return \Pimcore\Model\DataObject\Classificationstore\KeyConfig|null
     */
    public function getKeyConfigById(int $id): ?KeyConfig
    {
        return KeyConfig::getById($id, true);
    }

    /**
     * Process key config - if is sent input will handle whole keyConfig structure and save it
     *
     * @param int $classificationStoreId
     * @param string $name
     * @param \Pimcore\Model\DataObject\ClassDefinition\Data\TypeDeclarationSupportInterface|null $input
     * @param string|null $description
     *
     * @throws \Exception
     * @return \Pimcore\Model\DataObject\Classificationstore\KeyConfig|null
     */
    public function processKeyConfig(
        int $classificationStoreId,
        string $name,
        ?TypeDeclarationSupportInterface $input = null,
        ?string $description = null
    ): ?KeyConfig {
        // check for existing key config
        $keyConfig = KeyConfig::getByName($name, $classificationStoreId);

        if ($input instanceof TypeDeclarationSupportInterface) {
            // get name from input
            $name = strtolower($input->getName());

            // if was found and type is not as input so delete
            if ($keyConfig instanceof KeyConfig && $keyConfig->getType() !== $input->getFieldtype()) {
                $keyConfig->delete();
                $keyConfig = null;
            }

            if ($keyConfig instanceof KeyConfig) {
                // when quantity value exists check if it has the same units and push it to valid units
                if ($input instanceof QuantityValue) {
                    $definition = json_decode($keyConfig->getDefinition());

                    // check if unit is not already in validUnits
                    if (!is_array($definition->validUnits)) {
                        $definition->validUnits = [$input->getDefaultUnit()];
                    } elseif (!in_array($input->getDefaultUnit(), $definition->validUnits, true)) {
                        $definition->validUnits[] = $input->getDefaultUnit();
                    }

                    $input->setValidUnits($definition->validUnits);
                    $keyConfig->setDefinition(json_encode($input));
                    $keyConfig->save();
                }

                // if there is an existing keyConfig merge the values
                if ($input instanceof Select) {
                    $definition = json_decode($keyConfig->getDefinition(), true);
                    $definition['options'] = array_merge($input->getOptions(), $definition['options']);
                    $input->setOptions(array_unique($definition['options'], SORT_REGULAR));
                    $keyConfig->setDefinition(json_encode($input));
                    $keyConfig->save();
                }
            } else {
                $keyConfig = new KeyConfig();
                $keyConfig->setName($name);
            }

            $keyConfig->setTitle($input->getTitle());
            $keyConfig->setEnabled(true);
            if (!empty($description)) {
                $keyConfig->setDescription($description);
            }
            $keyConfig->setEnabled(true);
            $keyConfig->setType($input->getFieldtype());
            $keyConfig->setDefinition(json_encode($input));
            $keyConfig->setStoreId($classificationStoreId);
            $keyConfig->save();
        }

        return $keyConfig;
    }

    /**
     * @param int $id
     *
     * @return \Pimcore\Model\DataObject\Classificationstore\GroupConfig|null
     */
    public function getGroupConfigById(int $id): ?GroupConfig
    {
        return GroupConfig::getById($id, true);
    }

    /**
     * @param int $classificationStoreId
     * @param string $name
     * @param string|null $description
     *
     * @throws \Exception
     * @return \Pimcore\Model\DataObject\Classificationstore\GroupConfig
     */
    public function processGroupConfig(
        int $classificationStoreId,
        string $name,
        ?string $description = null
    ): GroupConfig {
        $slugify = new Slugify();
        $nameSlugged = $slugify->slugify($name, ['lowercase']);

        $groupConfig = GroupConfig::getByName($nameSlugged, $classificationStoreId);
        if (!$groupConfig instanceof GroupConfig) {
            $groupConfig = new GroupConfig();
            $groupConfig->setName($nameSlugged);
        }

        if (!empty($description)) {
            $groupConfig->setDescription($description);
        }
        $groupConfig->setStoreId($classificationStoreId);
        $groupConfig->save();

        return $groupConfig;
    }

    /**
     * @param int $classificationStoreId
     * @param string $name
     * @param string|null $description
     *
     * @throws \Exception
     * @return \Pimcore\Model\DataObject\Classificationstore\CollectionConfig
     */
    public function processCollectionConfig(
        int $classificationStoreId,
        string $name,
        ?string $description = null
    ): CollectionConfig {
        $name = strtolower($name);
        $collectionConfig = CollectionConfig::getByName($name, $classificationStoreId);
        if (!$collectionConfig instanceof CollectionConfig) {
            $collectionConfig = new CollectionConfig();
            $collectionConfig->setName($name);
        }

        if (!empty($description)) {
            $collectionConfig->setDescription($description);
        }
        $collectionConfig->setStoreId($classificationStoreId);
        $collectionConfig->save();

        return $collectionConfig;
    }

    /**
     * @param \Pimcore\Model\DataObject\Classificationstore\GroupConfig $groupConfig
     * @param \Pimcore\Model\DataObject\Classificationstore\CollectionConfig $collectionConfig
     * @param int $sorter
     *
     * @return \Pimcore\Model\DataObject\Classificationstore\CollectionGroupRelation
     */
    public function processCollectionGroupRelation(
        GroupConfig $groupConfig,
        CollectionConfig $collectionConfig,
        int $sorter = 0
    ): CollectionGroupRelation {
        $collectionGroupRelation = CollectionGroupRelation::getByGroupAndColId($groupConfig->getId(), $collectionConfig->getId());
        if (!$collectionGroupRelation instanceof CollectionGroupRelation) {
            $collectionGroupRelation = new CollectionGroupRelation();
            $collectionGroupRelation->setGroupId($groupConfig->getId());
            $collectionGroupRelation->setColId($collectionConfig->getId());
        }

        // must be saved here because getByGroupAndColId return instance
        $collectionGroupRelation->setSorter($sorter);
        $collectionGroupRelation->save();

        return $collectionGroupRelation;
    }

    /**
     * @param \Pimcore\Model\DataObject\Classificationstore\KeyConfig $keyConfig
     * @param \Pimcore\Model\DataObject\Classificationstore\GroupConfig $groupConfig
     * @param int $sorter
     *
     * @return \Pimcore\Model\DataObject\Classificationstore\KeyGroupRelation
     */
    public function processKeyGroupRelation(
        KeyConfig $keyConfig,
        GroupConfig $groupConfig,
        int $sorter = 0
    ): KeyGroupRelation {
        $keyGroupRelation = KeyGroupRelation::getByGroupAndKeyId($groupConfig->getId(), $keyConfig->getId());
        if (!$keyGroupRelation instanceof KeyGroupRelation) {
            $keyGroupRelation = new KeyGroupRelation();
            $keyGroupRelation->setGroupId($groupConfig->getId());
            $keyGroupRelation->setKeyId($keyConfig->getId());
        }

        // must be saved here because getByGroupAndKeyId return instance
        $keyGroupRelation->setSorter($sorter);
        $keyGroupRelation->save();

        return $keyGroupRelation;
    }
}
