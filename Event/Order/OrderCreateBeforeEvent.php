<?php

namespace Extension\Shop\Event\Order;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class OrderCreateBeforeEvent extends Event
{
    const NAME = 'order.event.beforeCreate';
    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }


} 