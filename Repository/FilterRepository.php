<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\ExtendRepositoryTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;

class FilterRepository extends DocumentRepository
{
    use ExtendRepositoryTrait;

    public function findAllByCategoryId($categoryId)
    {
        return $this->findBy(['categoryId' => $categoryId]);
    }
} 