<?php

namespace Extension\Shop\Converter;

use BinCMS\Converter\ConverterEventInterface;
use BinCMS\Converter\ConverterInterface;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Document\Order;

class OrderConverter implements ConverterInterface
{
    public function convert($value, ConverterService $converterService, ConverterEventInterface $event)
    {
        return $this->convertValue($value, $converterService, $event);
    }

    private function convertValue(Order $order, ConverterService $converterService, ConverterEventInterface $event)
    {
        $result = [
            'id' => $order->getId(),
            'number' => $order->getNumberFormatted(),
            'created' => $order->getCreated()->format('c'),
            'reserve' => $order->getReserve()->format('c'),
            'delivery' => $converterService->convert($order->getDelivery()),
            'address' => $converterService->convert($order->getAddress()),
            'products' => $converterService->convert($order->getProducts()),
            'status' => $converterService->convert($order->getStatus()),
            'isCanceled' => $order->getIsCanceled(),
            'totalPrice' => $order->getTotalPriceRetail()
        ];

        if($event->isDetailed()) {
        }

        return $result;
    }
}