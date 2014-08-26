<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\ExtendRepositoryTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Extension\Shop\Document\Manufacturer;
use Extension\Shop\Repository\Interfaces\ManufacturerRepositoryInterface;
use Extension\Shop\Repository\Traits\DocumentRepositoryCounted;
use Extension\Shop\Repository\Traits\DocumentRepositoryFindAllWithFilteredMethod;

class ManufacturerRepository extends DocumentRepository implements \Countable, ManufacturerRepositoryInterface
{
    use ExtendRepositoryTrait;
    use DocumentRepositoryCounted;
    use DocumentRepositoryFindAllWithFilteredMethod;

}