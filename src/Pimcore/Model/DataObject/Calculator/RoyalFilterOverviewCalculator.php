<?php

namespace App\Pimcore\Model\DataObject\Calculator;

use Pimcore\Model\DataObject\ClassDefinition\CalculatorClassInterface;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Data\CalculatedValue;
use Pimcore\Model\DataObject\RoyalFilter;
use Pimcore\Model\DataObject\Whirlpool;

class RoyalFilterOverviewCalculator implements CalculatorClassInterface
{
    /**
     * @param \Pimcore\Model\DataObject\Concrete $object
     * @param \Pimcore\Model\DataObject\Data\CalculatedValue $context
     *
     * @return string
     */
    public function compute(Concrete $object, CalculatedValue $context): string
    {
        if (!$object instanceof Whirlpool) {
            return '';
        }

        $html = '';
        $locale = $context->getPosition();

        $royalFilterSetup = $object->getRoyalFilterSetup();
        if ($royalFilterSetup instanceof RoyalFilter) {
            $totalHeight = $royalFilterSetup->getBody1()?->getLength() + $royalFilterSetup->getBody2()?->getLength();
            $diameter = $royalFilterSetup->getBody1()?->getDiameter();
            $centerDiameterFrom1 = $royalFilterSetup->getCenterBody1()?->getDiameterFrom();
            $centerDiameterTo1 = $royalFilterSetup->getCenterBody1()?->getDiameterTo();
            $equipBody1 = $royalFilterSetup->getEquipBody1()?->getTitle($locale);
            $equipBody2 = $royalFilterSetup->getEquipBody2()?->getTitle($locale);

            $html = '<table class="royal-filter-overview">';
            $html .= '<tr><td>Total height:</td><td>' . $totalHeight . '</td></tr>';
            $html .= '<tr><td>Diameter:</td><td>' . $diameter . '</td></tr>';
            $html .= '<tr><td>Center:</td><td>' . $centerDiameterFrom1 . '/' . $centerDiameterTo1 . '</td></tr>';
            $html .= $equipBody1 ? '<tr><td>Top equipment:</td><td>' . $equipBody2 . '</td></tr>' : '';
            $html .= $equipBody2 ? '<tr><td>Bottom equipment:</td><td>' . $equipBody1 . '</td></tr>' : '';
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
