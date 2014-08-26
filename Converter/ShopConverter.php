<?php

namespace Extension\Shop\Converter;

use BinCMS\Converter\ConverterEventInterface;
use BinCMS\Converter\ConverterInterface;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Document\Shop;

class ShopConverter implements ConverterInterface
{
    public function convert($value, ConverterService $converterService, ConverterEventInterface $event)
    {
        return $this->convertValue($value, $converterService, $event);
    }

    private function convertValue(Shop $shop, ConverterService $converterService, ConverterEventInterface $event)
    {
        return [
            'id' => $shop->getId(),
            'title' => $shop->getTitle(),
            'address' => $converterService->convert($shop->getAddress()),
            'location' => $converterService->convert($shop->getLocation()),
            'shedule' => $converterService->convert($shop->getShedule()),
            'warehouses' => $converterService->convert($shop->getWarehouses())
        ];
    }
}