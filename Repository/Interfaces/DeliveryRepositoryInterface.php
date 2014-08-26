<?php

namespace Extension\Shop\Repository\Interfaces;

use BinCMS\Repository\Interfaces\FilteredRepositoryInterface;
use BinCMS\Repository\ObjectRepository;

interface DeliveryRepositoryInterface extends ObjectRepository, FilteredRepositoryInterface
{
    const TP_DELIVERY = 0;
    const TP_PICKUP = 1;
} 