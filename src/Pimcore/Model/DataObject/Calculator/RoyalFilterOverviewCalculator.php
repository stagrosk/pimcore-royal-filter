<?php

namespace App\Pimcore\Model\DataObject\Calculator;

use App\Pimcore\ClassificationStore\ClassificationStoreHelper;
use Pimcore\Model\DataObject\ClassDefinition\CalculatorClassInterface;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Data\CalculatedValue;
use Pimcore\Model\DataObject\RoyalFilter;
use Pimcore\Model\DataObject\Whirlpool;

readonly class RoyalFilterOverviewCalculator implements CalculatorClassInterface
{

    /**
     * @param \Pimcore\Model\DataObject\Concrete $object
     * @param \Pimcore\Model\DataObject\Data\CalculatedValue $context
     *
     * @return string
     */
    public function compute(Concrete $object, CalculatedValue $context): string
    {
        $classificationStoreHelper = \Pimcore::getContainer()->get(ClassificationStoreHelper::class);

        if (!$object instanceof Whirlpool) {
            return '';
        }

        $html = '';
        $locale = $context->getPosition();

        $royalFilterSetup = $object->getRoyalFilterSetup();
        if ($royalFilterSetup instanceof RoyalFilter) {
            $mappedBody1 = $classificationStoreHelper->getClassificationStoreMapped($royalFilterSetup->getBody1()?->getMetadata());
            $mappedBody2 = $classificationStoreHelper->getClassificationStoreMapped($royalFilterSetup->getBody2()?->getMetadata());
            $mappedCenter1 = $classificationStoreHelper->getClassificationStoreMapped($royalFilterSetup->getCenterBody1()?->getMetadata());
//            $mappedCenter2 = $classificationStoreHelper->getClassificationStoreMapped($royalFilterSetup->getCenterBody2()?->getMetadata());
//            $mappedEquip1 = $classificationStoreHelper->getClassificationStoreMapped($royalFilterSetup->getEquipBody1()?->getMetadata());
//            $mappedEquip2 = $classificationStoreHelper->getClassificationStoreMapped($royalFilterSetup->getEquipBody2()?->getMetadata());

            $html = '<table class="royal-filter-overview">';

            $totalHeight = $mappedBody1 && $mappedBody2 ? ($mappedBody1['height']['rawValue'] + $mappedBody2['height']['rawValue'] . ' ' . $mappedBody1['height']['unit']) : '-';
            $html .= '<tr><td>Total height:</td><td>' . $totalHeight . '</td></tr>';

            $diameter = $mappedBody1 ? $mappedBody1['diameter']['value'] : '-';
            $html .= '<tr><td>Diameter:</td><td>' . $diameter . '</td></tr>';

            $centerDiameterFrom1 = $mappedCenter1 ? $mappedCenter1['diameterFrom']['value'] : '-';
            $centerDiameterTo1 = $mappedCenter1 ? $mappedCenter1['diameterTo']['value'] : '-';
            $html .= '<tr><td>Center:</td><td>' . $centerDiameterFrom1 . ' -> '  . $centerDiameterTo1 . '</td></tr>';

            $equipBody1 = $royalFilterSetup->getEquipBody1();
            $equipBody2 = $royalFilterSetup->getEquipBody2();
            $html .= $equipBody1 ? '<tr><td>Top equipment:</td><td>' . $equipBody1->getTitle($locale) . '</td></tr>' : '';
            $html .= $equipBody2 ? '<tr><td>Bottom equipment:</td><td>' . $equipBody2->getTitle($locale) . '</td></tr>' : '';

            $html .= '</table>';
        }

        return $html;
    }

    /**
     * @param \Pimcore\Model\DataObject\Concrete $object
     * @param \Pimcore\Model\DataObject\Data\CalculatedValue $context
     *
     * @return string
     */
    public function getCalculatedValueForEditMode(Concrete $object, CalculatedValue $context): string
    {
        return $this->compute($object, $context);
    }
}
