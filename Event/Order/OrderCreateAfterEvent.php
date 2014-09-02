<?php

namespace Extension\Shop\Event\Order;

use Extension\Shop\Document\Order;
use Symfony\Component\EventDispatcher\Event;

class OrderCreateAfterEvent extends Event
{
    const NAME = 'order.event.afterCreate';
    /**
     * @var
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