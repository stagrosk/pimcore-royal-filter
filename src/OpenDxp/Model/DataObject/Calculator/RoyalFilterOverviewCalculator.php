<?php

namespace App\OpenDxp\Model\DataObject\Calculator;

use App\OpenDxp\Model\DataObject\RoyalFilter;
use App\Service\ProductMetadataService;
use OpenDxp\Model\DataObject\ClassDefinition\CalculatorClassInterface;
use OpenDxp\Model\DataObject\Concrete;
use OpenDxp\Model\DataObject\Data\CalculatedValue;
use OpenDxp\Model\DataObject\Fieldcollection\Data\RoyalFilterSetup;
use OpenDxp\Model\DataObject\Whirlpool;

readonly class RoyalFilterOverviewCalculator implements CalculatorClassInterface
{
    /**
     * @param \OpenDxp\Model\DataObject\Concrete $object
     * @param \OpenDxp\Model\DataObject\Data\CalculatedValue $context
     *
     * @return string
     */
    public function compute(Concrete $object, CalculatedValue $context): string
    {
        $html = '';
        if (!$object instanceof Whirlpool) {
            return $html;
        }


        $locale = $context->getPosition();

        $royalFilterSetups = $object->getRoyalFilterSetups()?->getItems();
        /** @var \OpenDxp\Model\DataObject\Fieldcollection\Data\RoyalFilterSetup|null $royalFilterSetupFieldcollection */
        $royalFilterSetupFieldcollection = $royalFilterSetups[$context->getIndex()] ?? null;
        if ($royalFilterSetupFieldcollection instanceof RoyalFilterSetup) {
            $royalFilterSetup = $royalFilterSetupFieldcollection->getRoyalFilterSetup();

            $overrides = [
                'adapter' => $royalFilterSetupFieldcollection->getAdapter(),
                'equipBody1' => $royalFilterSetupFieldcollection->getEquipBody1(),
                'equipBody2' => $royalFilterSetupFieldcollection->getEquipBody2(),
            ];

            $dimensions = $this->calculateFinalDimensions($royalFilterSetup, $overrides);

            $html = '<table class="royal-filter-overview">';
            $html .= '<tr><td>Total height:</td><td>' . ($dimensions['body']['height'] ? $dimensions['body']['height'] . ' ' . $dimensions['body']['unit'] : '-') . '</td></tr>';
            $html .= '<tr><td>Diameter:</td><td>' . $dimensions['body']['diameter'] . ' ' . $dimensions['body']['diameterUnit'] . '</td></tr>';

            if ($dimensions['center']['diameterFrom1'] !== $dimensions['center']['diameterTo1']) {
                $html .= '<tr><td>Center:</td><td>' . $dimensions['center']['diameterFrom1'] . $dimensions['center']['diameterFrom1Unit'] . ' -> '  . $dimensions['center']['diameterTo1'] . $dimensions['center']['diameterTo1Unit']. '</td></tr>';
            } else {
                $html .= '<tr><td>Center:</td><td>' . $dimensions['center']['diameterFrom1'] . $dimensions['center']['diameterFrom1Unit'] . '</td></tr>';
            }

            $equipBody1 = $royalFilterSetup->getEquipBody1();
            $equipBody2 = $royalFilterSetup->getEquipBody2();
            $html .= $equipBody1 ? '<tr><td>Top equipment:</td><td>' . $equipBody1->getTitle($locale) . '</td></tr>' : '';
            $html .= $equipBody2 ? '<tr><td>Bottom equipment:</td><td>' . $equipBody2->getTitle($locale) . '</td></tr>' : '';

            $html .= '</table>';
        }

        return $html;
    }

    /**
     * @param \OpenDxp\Model\DataObject\Concrete $object
     * @param \OpenDxp\Model\DataObject\Data\CalculatedValue $context
     *
     * @return string
     */
    public function getCalculatedValueForEditMode(Concrete $object, CalculatedValue $context): string
    {
        return $this->compute($object, $context);
    }

    /**
     * @param \App\OpenDxp\Model\DataObject\RoyalFilter $royalFilterSetup
     * @param array $overrides
     *
     * @return array
     */
    private function calculateFinalDimensions(RoyalFilter $royalFilterSetup, array $overrides = []): array
    {
        $dimensions = [];

        $productMetadataService = \OpenDxp::getContainer()->get(ProductMetadataService::class);
        $mappedParameters = $productMetadataService->getMappedParametersOfParts($royalFilterSetup, $overrides);

        $body1Height = isset($mappedParameters['body1']) ? $mappedParameters['body1']['mapping']?->findItemByKeyConfigName('body', 'height') : null;
        $body2Height = isset($mappedParameters['body2']) ? $mappedParameters['body2']['mapping']?->findItemByKeyConfigName('body', 'height') : null;
        $bodyMiddleHeight = isset($mappedParameters['bodyMiddle']) ? $mappedParameters['bodyMiddle']['mapping']?->findItemByKeyConfigName('body', 'height') : null;
        $dimensions['body']['height'] = ($body1Height?->getRawValue() ?? 0) + ($bodyMiddleHeight?->getRawValue() ?? 0) + ($body2Height?->getRawValue() ?? 0);
        $dimensions['body']['unit'] = $body1Height?->getUnit();

        $body1Diameter = isset($mappedParameters['body1']) ? $mappedParameters['body1']['mapping']?->findItemByKeyConfigName('body', 'diameter') : null;
        $dimensions['body']['diameter'] = $body1Diameter?->getRawValue();
        $dimensions['body']['diameterUnit'] = $body1Diameter?->getUnit();

        $center1DiameterFrom = isset($mappedParameters['center1']) ? $mappedParameters['center1']['mapping']?->findItemByKeyConfigName('center', 'centerDiameterFrom') : null;
        $center1DiameterTo = isset($mappedParameters['center1']) ? $mappedParameters['center1']['mapping']?->findItemByKeyConfigName('center', 'centerDiameterTo') : null;
        $dimensions['center']['diameterFrom1'] = $center1DiameterFrom?->getRawValue();
        $dimensions['center']['diameterFrom1Unit'] = $center1DiameterFrom?->getUnit();
        $dimensions['center']['diameterTo1'] = $center1DiameterTo?->getRawValue();
        $dimensions['center']['diameterTo1Unit'] = $center1DiameterTo?->getUnit();

        return $dimensions;
    }
}
