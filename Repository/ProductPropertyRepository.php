<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\ExtendRepositoryTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Extension\Shop\Repository\Interfaces\ProductPropertyRepositoryInterface;

class ProductPropertyRepository extends DocumentRepository implements ProductPropertyRepositoryInterface
{
    use ExtendRepositoryTrait;

    public function findByExternalId($externalId)
    {
        return $this->findOneBy(['externalId' => $externalId]);
    }
}