<?php

namespace Extension\Shop\Repository\Interfaces;

use BinCMS\Repository\ObjectRepository;

interface ProductPropertyRepositoryInterface extends ObjectRepository
{
    public function findByExternalId($externalId);
} 