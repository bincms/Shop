<?php

namespace Extension\Shop\Converter;

use BinCMS\Converter\ConverterEventInterface;
use BinCMS\Converter\ConverterInterface;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Document\Delivery;

class DeliveryConverter implements ConverterInterface
{
    public function convert($value, ConverterService $converterService, ConverterEventInterface $event)
    {
        return $this->convertValue($value, $converterService);
    }

    private function convertValue(Delivery $delivery, ConverterService $converterService)
    {
        return [
            'id' => $delivery->getId(),
            'title' => $delivery->getTitle(),
            'description' => $delivery->getDescription(),
            'price' => $delivery->getPrice(),
            'enabled' => $delivery->getEnabled()
        ];
    }
}