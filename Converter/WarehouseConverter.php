<?php

namespace Extension\Shop\Converter;

use BinCMS\Converter\ConverterEventInterface;
use BinCMS\Converter\ConverterInterface;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Document\Warehouse;

class WarehouseConverter implements ConverterInterface
{
    public function convert($value, ConverterService $converterService, ConverterEventInterface $event)
    {
        return $this->convertValue($value, $converterService, $event);
    }

    private function convertValue(Warehouse $warehouse, ConverterService $converterService, ConverterEventInterface $event)
    {
        return [
            'id' => $warehouse->getId(),
            'title' => $warehouse->getTitle()
        ];
    }
}