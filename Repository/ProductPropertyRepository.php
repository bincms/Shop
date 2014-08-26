<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\RepositoryExtendTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Extension\Shop\Repository\Interfaces\ProductPropertyRepositoryInterface;

class ProductPropertyRepository extends DocumentRepository implements ProductPropertyRepositoryInterface
{
    use RepositoryExtendTrait;

    public function findByExternalId($externalId)
    {
        return $this->findOneBy(['externalId' => $externalId]);
    }
}