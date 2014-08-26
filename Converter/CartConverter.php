<?php

namespace Extension\Shop\Converter;

use BinCMS\Converter\ConverterEventInterface;
use BinCMS\Converter\ConverterInterface;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Document\Cart;

class CartConverter implements ConverterInterface
{
    public function convert($value, ConverterService $converterService, ConverterEventInterface $event)
    {
        return $this->convertValue($value, $converterService, $event);
    }

    private function convertValue(Cart $cart, ConverterService $converterService, ConverterEventInterface $event)
    {

        $items = $cart->getItems();

        return [
            'id' => $cart->getId(),
            'count' => sizeof($items),
            'items' => $converterService->convert($items),
            'totalPrice' => $cart->getTotalPrice()
        ];
    }


}