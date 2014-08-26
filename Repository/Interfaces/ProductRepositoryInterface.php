<?php

namespace Extension\Shop\Repository\Interfaces;

use BinCMS\Repository\Interfaces\FilteredRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;

interface ProductRepositoryInterface extends ObjectRepository, FilteredRepositoryInterface
{
    public function findAllWithCategoryId($categoryId);
}