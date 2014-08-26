<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\RepositoryExtendTrait;
use BinCMS\RepositoryTrait\RepositoryFilteredTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Extension\Shop\Repository\Interfaces\DeliveryRepositoryInterface;
use Extension\Shop\Repository\Traits\DocumentRepositoryCounted;

class DeliveryRepository extends DocumentRepository implements \Countable, DeliveryRepositoryInterface
{
    use RepositoryExtendTrait;
    use DocumentRepositoryCounted;
    use RepositoryFilteredTrait;
}