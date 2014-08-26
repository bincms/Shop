<?php

namespace Extension\Shop\Converter;

use BinCMS\Converter\ConverterEventInterface;
use BinCMS\Converter\ConverterInterface;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Document\CartItem;

class CartItemConverter implements ConverterInterface
{

    public function convert($value, ConverterService $converterService, ConverterEventInterface $event)
    {
        return $this->convertValue($value, $converterService, $event);
    }

    private function convertValue(CartItem $cartItem, ConverterService $converterService, ConverterEventInterface $event)
    {
        return [
            'quantity' => $cartItem->getQuantity(),
            'product' => $converterService->convert($cartItem->getProduct()),
            'totalPrice' => $cartItem->getTotalPrice()
        ];
    }
}