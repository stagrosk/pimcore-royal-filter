<?php

namespace App\Shopify\Model\PriceList;

use App\Shopify\Model\IShopifyModel;

final class PriceListParentCreateInput implements IShopifyModel
{
    /**
     * @param \App\Shopify\Model\PriceList\PriceListAdjustmentInput|null $adjustment
     * @param \App\Shopify\Model\PriceList\PriceListAdjustmentSettingsInput|null $adjustmentSettings
     */
    public function __construct(
        private ?PriceListAdjustmentInput         $adjustment = null,
        private ?PriceListAdjustmentSettingsInput $adjustmentSettings = null
    ) {

        if (empty($this->getAdjustment())) {
            $this->adjustment = new PriceListAdjustmentInput();
        }

        if (empty($this->getAdjustment())) {
            $this->adjustmentSettings = new PriceListAdjustmentSettingsInput();
        }
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'adjustment' => $this->getAdjustment()->getAsArray(),
            'settings' => $this->getAdjustmentSettings()->getAsArray(),
        ];
    }

    public function getAdjustment(): ?PriceListAdjustmentInput
    {
        return $this->adjustment;
    }

    public function setAdjustment(?PriceListAdjustmentInput $adjustment): void
    {
        $this->adjustment = $adjustment;
    }

    public function getAdjustmentSettings(): ?PriceListAdjustmentSettingsInput
    {
        return $this->adjustmentSettings;
    }

    public function setAdjustmentSettings(?PriceListAdjustmentSettingsInput $adjustmentSettings): void
    {
        $this->adjustmentSettings = $adjustmentSettings;
    }
}
