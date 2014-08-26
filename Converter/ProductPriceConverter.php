<?php

namespace Extension\Shop\Converter;

use BinCMS\Converter\ConverterEventInterface;
use BinCMS\Converter\ConverterInterface;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Document\ProductPrice;

class ProductPriceConverter implements ConverterInterface
{
    public function convert($value, ConverterService $converterService, ConverterEventInterface $event)
    {
        return $this->convertValue($value, $converterService);
    }

    private function convertValue(ProductPrice $productPrice, ConverterService $converterService)
    {
        return [
            'pctSavings' => $productPrice->getPctSavings(),
            'savings' => $productPrice->getSavings(),
            'trade' => $productPrice->getTrade(),
            'retail' =>  $productPrice->getRetail()
        ];
    }
}