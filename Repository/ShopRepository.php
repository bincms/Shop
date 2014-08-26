<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\ExtendRepositoryTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Extension\Shop\Repository\Interfaces\ShopRepositoryInterface;
use Extension\Shop\Repository\Traits\DocumentRepositoryFindAllWithFilteredMethod;

class ShopRepository extends DocumentRepository implements ShopRepositoryInterface
{
    use ExtendRepositoryTrait;
    use DocumentRepositoryFindAllWithFilteredMethod;
} 