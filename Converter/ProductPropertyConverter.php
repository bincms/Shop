<?php

namespace Extension\Shop\Converter;

use BinCMS\Converter\ConverterEventInterface;
use BinCMS\Converter\ConverterInterface;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Document\ProductProperty;

class ProductPropertyConverter implements ConverterInterface
{
    public function convert($value, ConverterService $converterService, ConverterEventInterface $event)
    {
        return $this->convertValue($value, $converterService, $event);
    }

    private function convertValue(ProductProperty $productProperty, ConverterService $converterService, ConverterEventInterface $event)
    {
        return [
            'title' => $productProperty->getTitle()
        ];
    }
}