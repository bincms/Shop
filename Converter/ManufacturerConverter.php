<?php

namespace Extension\Shop\Converter;

use BinCMS\Converter\ConverterEventInterface;
use BinCMS\Converter\ConverterInterface;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Document\Manufacturer;

class ManufacturerConverter implements ConverterInterface
{
    public function convert($value, ConverterService $converterService, ConverterEventInterface $event)
    {
        return $this->convertValue($value, $converterService);
    }

    private function convertValue(Manufacturer $manufacturer, ConverterService $converterService)
    {
        return [
            'id' => $manufacturer->getId(),
            'title' => $manufacturer->getTitle(),
            'url' => $manufacturer->getUrl()
        ];
    }
}