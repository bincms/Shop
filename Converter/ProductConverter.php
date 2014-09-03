<?php

namespace Extension\Shop\Converter;

use BinCMS\Converter\ConverterEventInterface;
use BinCMS\Converter\ConverterInterface;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Document\Product;

class ProductConverter implements ConverterInterface
{
    public function convert($value, ConverterService $converterService, ConverterEventInterface $event)
    {
        return $this->convertValue($value, $converterService, $event);
    }

    private function convertValue(Product $product, ConverterService $converterService, ConverterEventInterface $event)
    {
        $result = [
            'id' => $product->getId(),
            'sku' => $product->getSku(),
            'price' => $converterService->convert($product->getPrice()),
            'title' => $product->getTitle(),
            'availability' => $product->getAvailability(),
            'totalAvailability' => $product->getTotalAvailability(),
            'image' => $converterService->convert($product->getImage()),
            'unit' => $product->getUnit(),
            'isNew' => $product->getIsNew(),
            'isLeader' => $product->getIsLeader()
        ];

        if($event->isDetailed()) {
            $result['properties'] = $converterService->convert($product->getProperties());
            $result['category'] = $converterService->convert($product->getCategory());
            $result['description'] = nl2br($product->getDescription());
        }

        return $result;
    }
}