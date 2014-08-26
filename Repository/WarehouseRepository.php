<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\ExtendRepositoryTrait;
use BinCMS\RepositoryTrait\RepositoryExternalTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Extension\Shop\Repository\Interfaces\WarehouseRepositoryInterface;
use Extension\Shop\Repository\Traits\DocumentRepositoryCounted;
use Extension\Shop\Repository\Traits\DocumentRepositoryFindAllWithFilteredMethod;

class WarehouseRepository extends DocumentRepository implements \Countable, WarehouseRepositoryInterface
{
    use ExtendRepositoryTrait;
    use DocumentRepositoryCounted;
    use DocumentRepositoryFindAllWithFilteredMethod;
    use RepositoryExternalTrait;
}