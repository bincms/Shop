<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\ExtendRepositoryTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Extension\Shop\Repository\Interfaces\OrderStatusRepositoryInterface;
use Extension\Shop\Repository\Traits\DocumentRepositoryCounted;
use Extension\Shop\Repository\Traits\DocumentRepositoryFindAllWithFilteredMethod;

class OrderStatusRepository extends DocumentRepository implements \Countable, OrderStatusRepositoryInterface
{
    use \BinCMS\RepositoryTrait\ExtendRepositoryTrait;
    use DocumentRepositoryCounted;
    use DocumentRepositoryFindAllWithFilteredMethod;
}