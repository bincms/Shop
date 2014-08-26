<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\RepositoryExtendTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;

class FilterRepository extends DocumentRepository
{
    use RepositoryExtendTrait;

    public function findAllByCategoryId($categoryId)
    {
        return $this->findBy(['categoryId' => $categoryId]);
    }
} 