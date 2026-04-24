<?php

declare(strict_types=1);

namespace App\Service;

use OpenDxp\Model\DataObject\ProductOption;
use OpenDxp\Model\DataObject\ProductOptionGroup;
use OpenDxp\Model\DataObject\Service;

class ProductOptionService
{
    private const OPTIONS_PATH = '/Options';

    /**
     * Get or create a ProductOptionGroup by code
     *
     * @param string $code
     * @param string $name
     *
     * @return ProductOptionGroup
     * @throws \Exception
     */
    public function getOrCreateOptionGroup(string $code, string $name): ProductOptionGroup
    {
        // Try to find existing
        $optionGroup = ProductOptionGroup::getByCode($code, 1);

        if ($optionGroup instanceof ProductOptionGroup) {
            return $optionGroup;
        }

        // Create new
        $optionGroup = new ProductOptionGroup();
        $optionGroup->setCode($code);
        $optionGroup->setName($name);
        $optionGroup->setParent(Service::createFolderByPath(self::OPTIONS_PATH . '/Groups'));
        $optionGroup->setKey(Service::getValidKey($code, 'object'));
        $optionGroup->setPublished(true);
        $optionGroup->save();

        return $optionGroup;
    }

    /**
     * Get or create a ProductOption by code
     *
     * @param string $code
     * @param string $name
     * @param ProductOptionGroup $group
     *
     * @return ProductOption
     * @throws \Exception
     */
    public function getOrCreateOption(string $code, string $name, ProductOptionGroup $group): ProductOption
    {
        // Try to find existing
        $option = ProductOption::getByCode($code, 1);

        if ($option instanceof ProductOption) {
            return $option;
        }

        // Create new under the group
        $option = new ProductOption();
        $option->setCode($code);
        $option->setName($name);
        $option->setParent($group);
        $option->setKey(Service::getValidKey($code, 'object'));
        $option->setPublished(true);
        $option->save();

        return $option;
    }

    /**
     * Generate a code from group and key names
     *
     * @param string $groupName
     * @param string $keyName
     *
     * @return string
     */
    public function generateOptionGroupCode(string $groupName, string $keyName): string
    {
        return sprintf(
            '%s_%s',
            $this->sanitizeCode($groupName),
            $this->sanitizeCode($keyName)
        );
    }

    /**
     * Generate a code for option value
     *
     * @param string $groupCode
     * @param mixed $value
     *
     * @return string
     */
    public function generateOptionCode(string $groupCode, mixed $value): string
    {
        $valueStr = is_array($value) || is_object($value) ? json_encode($value) : (string)$value;

        return sprintf(
            '%s_%s',
            $groupCode,
            $this->sanitizeCode($valueStr)
        );
    }

    /**
     * Sanitize string for use as code
     *
     * @param string $value
     *
     * @return string
     */
    private function sanitizeCode(string $value): string
    {
        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9]+/', '_', $value);
        $value = trim($value, '_');

        return $value;
    }
}