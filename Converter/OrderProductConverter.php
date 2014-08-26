<?php

namespace Extension\Shop\Converter;

use BinCMS\Converter\ConverterEventInterface;
use BinCMS\Converter\ConverterInterface;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Document\OrderProduct;

class OrderProductConverter implements ConverterInterface
{
    public function convert($value, ConverterService $converterService, ConverterEventInterface $event)
    {
        return $this->convertValue($value, $converterService, $event);
    }

    private function convertValue(OrderProduct $orderProduct, ConverterService $converterService, ConverterEventInterface $event)
    {
        return [
            'productId' => $orderProduct->getProductId(),
            'title' => $orderProduct->getTitle(),
            'quantity' => $orderProduct->getQuantity(),
            'price' => $converterService->convert($orderProduct->getPrice())
        ];
    }
}