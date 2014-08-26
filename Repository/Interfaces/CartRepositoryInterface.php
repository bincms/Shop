<?php

namespace Extension\Shop\Repository\Interfaces;

use BinCMS\Repository\ObjectRepository;

interface CartRepositoryInterface extends ObjectRepository
{
    /**
     * @param mixed $value
     * @return \Extension\Shop\Document\Cart
     */
    public function findByUserOrId($value);

    /**
     * @return void
     */
    public function truncate();
}