<?php

namespace OpendxpVendureBridgeBundle\Serialization\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\JsonSerializationVisitor;

class PriceHandler
{
    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param array $objects
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return array
     */
    public function serializeListPrice(JsonSerializationVisitor $visitor, array $objects, array $type, Context $context): array
    {
        $priceList = [];
        /** @var \OpenDxp\Model\DataObject\Price $price */
        foreach ($objects as $price) {
            $priceList[] = [
                'channel' => $price->getChannel(),
                'netPrice' => $price->getNetPrice(),
                'currency' => $price->getCurrency(),
            ];
        }

        return $priceList;
    }
}
