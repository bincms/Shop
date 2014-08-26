<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\RepositoryExtendTrait;
use BinCMS\RepositoryTrait\RepositoryFilteredTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Extension\Shop\Repository\Interfaces\ShopRepositoryInterface;

class ShopRepository extends DocumentRepository implements ShopRepositoryInterface
{
    use RepositoryExtendTrait;
    use RepositoryFilteredTrait;
} 