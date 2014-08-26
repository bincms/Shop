<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\ExtendRepositoryTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Extension\Shop\Repository\Interfaces\OrderRepositoryInterface;
use Extension\Shop\Repository\Traits\DocumentRepositoryCounted;
use Extension\Shop\Repository\Traits\DocumentRepositoryFindAllWithFilteredMethod;

class OrderRepository extends DocumentRepository implements \Countable, OrderRepositoryInterface
{
    use ExtendRepositoryTrait;
    use DocumentRepositoryCounted;
    use DocumentRepositoryFindAllWithFilteredMethod;
} 