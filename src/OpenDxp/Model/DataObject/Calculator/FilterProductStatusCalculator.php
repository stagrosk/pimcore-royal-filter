<?php

declare(strict_types=1);

namespace App\OpenDxp\Model\DataObject\Calculator;

use App\OpenDxp\Model\DataObject\FilterSet;
use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\ClassDefinition\CalculatorClassInterface;
use OpenDxp\Model\DataObject\Concrete;
use OpenDxp\Model\DataObject\Data\CalculatedValue;
use OpenDxp\Model\DataObject\Fieldcollection\Data\RoyalFilterSetup;
use OpenDxp\Model\DataObject\Product;
use OpenDxp\Model\DataObject\Whirlpool;

readonly class FilterProductStatusCalculator implements CalculatorClassInterface
{
    public function compute(Concrete $object, CalculatedValue $context): string
    {
        if (!$object instanceof Whirlpool) {
            return '';
        }

        $items = $object->getRoyalFilterSetups()?->getItems() ?? [];
        $fieldCollection = $items[$context->getIndex()] ?? null;
        if (!$fieldCollection instanceof RoyalFilterSetup) {
            return '';
        }

        $filterSet = $fieldCollection->getFilterSet();
        if (!$filterSet instanceof FilterSet) {
            return $this->renderBox('warning', 'No FilterSet selected.');
        }

        $product = $filterSet->getProduct();
        $generate = $filterSet->getGenerateAsProduct() === true;

        if ($product instanceof Product) {
            $isVariant = $product->getType() === AbstractObject::OBJECT_TYPE_VARIANT;
            $kind = $isVariant ? 'Variant' : 'Master';
            $apiId = $product->getApiId();

            $lines = [
                sprintf('Kind: %s', $kind),
                sprintf('Product #%d', $product->getId()),
                sprintf('SKU: %s', htmlspecialchars((string) $product->getSku(), ENT_QUOTES)),
                $apiId ? sprintf('Vendure apiId: %s', htmlspecialchars($apiId, ENT_QUOTES)) : 'Vendure apiId: not synced yet',
            ];

            if ($isVariant) {
                $master = $product->getParent();
                if ($master instanceof Product) {
                    $lines[] = sprintf(
                        'Master #%d (SKU: %s)',
                        $master->getId(),
                        htmlspecialchars((string) $master->getSku(), ENT_QUOTES)
                    );
                }
            }

            return $this->renderBox('ok', sprintf('Filter product exists (%s).', $kind), $lines);
        }

        if ($generate) {
            return $this->renderBox(
                'pending',
                'Filter product is queued for generation.',
                [sprintf('Save FilterSet #%d to generate the product.', $filterSet->getId())]
            );
        }

        return $this->renderBox(
            'missing',
            'No filter product exists for this FilterSet.',
            [sprintf('Open FilterSet #%d and enable "Generate as product", then save it.', $filterSet->getId())]
        );
    }

    public function getCalculatedValueForEditMode(Concrete $object, CalculatedValue $context): string
    {
        return $this->compute($object, $context);
    }

    /**
     * @param 'ok'|'pending'|'missing'|'warning' $state
     * @param string[] $lines
     */
    private function renderBox(string $state, string $title, array $lines = []): string
    {
        $colors = [
            'ok' => ['bg' => '#e6f4ea', 'border' => '#34a853', 'icon' => '✓'],
            'pending' => ['bg' => '#fef7e0', 'border' => '#f9ab00', 'icon' => '⏳'],
            'missing' => ['bg' => '#fce8e6', 'border' => '#d93025', 'icon' => '✗'],
            'warning' => ['bg' => '#fce8e6', 'border' => '#d93025', 'icon' => '!'],
        ];
        $c = $colors[$state];

        $body = '';
        foreach ($lines as $line) {
            $body .= '<div style="margin-top:2px;">' . htmlspecialchars($line, ENT_QUOTES) . '</div>';
        }

        return sprintf(
            '<div style="padding:6px 8px;background:%s;border-left:3px solid %s;font-size:12px;">'
            . '<strong>%s %s</strong>%s</div>',
            $c['bg'],
            $c['border'],
            $c['icon'],
            htmlspecialchars($title, ENT_QUOTES),
            $body
        );
    }
}
