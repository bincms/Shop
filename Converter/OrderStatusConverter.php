<?php

namespace Extension\Shop\Converter;

use BinCMS\Converter\ConverterEventInterface;
use BinCMS\Converter\ConverterInterface;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Document\OrderStatus;

class OrderStatusConverter implements ConverterInterface
{
    public function convert($value, ConverterService $converterService, ConverterEventInterface $event)
    {
        return $this->convertValue($value, $converterService);
    }

    private function convertValue(OrderStatus $orderStatus, ConverterService $converterService)
    {
        return [
            'id' => $orderStatus->getId(),
            'title' => $orderStatus->getTitle(),
        ];
    }
}