<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\RepositoryExtendTrait;
use BinCMS\RepositoryTrait\RepositoryFilteredTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Extension\Shop\Repository\Interfaces\OrderRepositoryInterface;
use Extension\Shop\Repository\Traits\DocumentRepositoryCounted;

class OrderRepository extends DocumentRepository implements \Countable, OrderRepositoryInterface
{
    use RepositoryExtendTrait;
    use DocumentRepositoryCounted;
    use RepositoryFilteredTrait;
} 