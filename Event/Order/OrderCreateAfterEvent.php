<?php

namespace Extension\Shop\Event\Order;

use Extension\Shop\Document\Order;

class OrderCreateAfterEvent
{
    const NAME = 'order.event.afterCreate';
    /**
     * @var Order
     */
    private $order;

    /**
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }


} 