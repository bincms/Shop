<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\RepositoryExtendTrait;
use BinCMS\RepositoryTrait\RepositoryFilteredTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Extension\Shop\Document\Manufacturer;
use Extension\Shop\Repository\Interfaces\ManufacturerRepositoryInterface;
use Extension\Shop\Repository\Traits\DocumentRepositoryCounted;

class ManufacturerRepository extends DocumentRepository implements \Countable, ManufacturerRepositoryInterface
{
    use RepositoryExtendTrait;
    use DocumentRepositoryCounted;
    use RepositoryFilteredTrait;

}