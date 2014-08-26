<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\RepositoryExtendTrait;
use BinCMS\RepositoryTrait\RepositoryFilteredTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Extension\Shop\Repository\Interfaces\OrderStatusRepositoryInterface;
use Extension\Shop\Repository\Traits\DocumentRepositoryCounted;

class OrderStatusRepository extends DocumentRepository implements \Countable, OrderStatusRepositoryInterface
{
    use RepositoryExtendTrait;
    use DocumentRepositoryCounted;
    use RepositoryFilteredTrait;
}