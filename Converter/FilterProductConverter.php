<?php

namespace Extension\Shop\Converter;

use BinCMS\Converter\ConverterEventInterface;
use BinCMS\Converter\ConverterInterface;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Document\Filter;

class FilterProductConverter implements ConverterInterface
{
    public function convert($value, ConverterService $converterService, ConverterEventInterface $event)
    {
        return $this->convertValue($value, $converterService, $event);
    }

    private function convertValue(Filter $filter, ConverterService $converterService, ConverterEventInterface $event)
    {
        return [
            'propertyId' => $filter->getPropertyId(),
            'title' => $filter->getTitle(),
            'values' => $converterService->convert($filter->getValues())
        ];
    }
}