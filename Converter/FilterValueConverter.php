<?php

namespace Extension\Shop\Converter;

use BinCMS\Converter\ConverterEventInterface;
use BinCMS\Converter\ConverterInterface;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Document\FilterValue;

class FilterValueConverter implements ConverterInterface
{

    public function convert($value, ConverterService $converterService, ConverterEventInterface $event)
    {
        return $this->convertValue($value, $converterService, $event);
    }

    private function convertValue(FilterValue $filterValue, ConverterService $converterService, ConverterEventInterface $event)
    {
        return [
            'title' => $filterValue->getTitle(),
            'value' => $filterValue->getValue(),
        ];
    }
}