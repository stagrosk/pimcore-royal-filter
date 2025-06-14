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
            $mappedBodyMiddle = $classificationStoreHelper->getClassificationStoreMapped($royalFilterSetup->getBodyMiddle()?->getMetadata());
            $mappedBody2 = $classificationStoreHelper->getClassificationStoreMapped($royalFilterSetup->getBody2()?->getMetadata());
            $mappedCenterBody1 = $classificationStoreHelper->getClassificationStoreMapped($royalFilterSetup->getCenterBody1()?->getMetadata());
//            $mappedCenterBodyMiddle = $classificationStoreHelper->getClassificationStoreMapped($royalFilterSetup->getCenterBodyMiddle()?->getMetadata());
//            $mappedCenterBody2 = $classificationStoreHelper->getClassificationStoreMapped($royalFilterSetup->getCenterBody2()?->getMetadata());
//            $mappedEquip1 = $classificationStoreHelper->getClassificationStoreMapped($royalFilterSetup->getEquipBody1()?->getMetadata());
//            $mappedEquip2 = $classificationStoreHelper->getClassificationStoreMapped($royalFilterSetup->getEquipBody2()?->getMetadata());

            $html = '<table class="royal-filter-overview">';

            $body1Height = $mappedBody1->findItemByKeyConfigName('body', 'height');
            $bodyMiddleHeight = $mappedBodyMiddle->findItemByKeyConfigName('body', 'height');
            $body2Height = $mappedBody2->findItemByKeyConfigName('body', 'height');
            $totalHeight = $body1Height?->getRawValue() + $bodyMiddleHeight?->getRawValue() + $body2Height?->getRawValue();
            $html .= '<tr><td>Total height:</td><td>' . ($totalHeight ? $totalHeight . ' ' . $body1Height->getUnit() : '-') . '</td></tr>';

            $body1Diameter = $mappedBody1->findItemByKeyConfigName('body', 'diameter');
            $diameter = $body1Diameter ? $body1Diameter->getValue() : '-';
            $html .= '<tr><td>Diameter:</td><td>' . $diameter . '</td></tr>';

            $center1DiameterFrom = $mappedCenterBody1->findItemByKeyConfigName('center', 'centerDiameterFrom');
            $center1DiameterTo = $mappedCenterBody1->findItemByKeyConfigName('center', 'centerDiameterTo');
            $centerDiameterFrom1 = $center1DiameterFrom ? $center1DiameterFrom->getValue() : '-';
            $centerDiameterTo1 = $center1DiameterTo ? $center1DiameterTo->getValue() : '-';
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
