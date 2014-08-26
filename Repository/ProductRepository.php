<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\ExtendRepositoryTrait;
use BinCMS\RepositoryTrait\RepositoryExternalTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Extension\Shop\Repository\Interfaces\ProductRepositoryInterface;
use Extension\Shop\Repository\Traits\DocumentRepositoryCounted;
use Extension\Shop\Repository\Traits\DocumentRepositoryFindAllWithFilteredMethod;

class ProductRepository extends DocumentRepository implements \Countable, ProductRepositoryInterface
{
    use ExtendRepositoryTrait;
    use DocumentRepositoryCounted;
    use DocumentRepositoryFindAllWithFilteredMethod;
    use RepositoryExternalTrait;

    /**
     * @param $categoryId
     * @return \Extension\Shop\Document\Product[]|null
     */
    public function findAllWithCategoryId($categoryId)
    {
        if(!($categoryId instanceof \MongoId)) {
            $categoryId = new \MongoId($categoryId);
        }

        return $this->findBy(['category' => $categoryId]);
    }

    public function loadInIds(array $ids)
    {
        return $this->createQueryBuilder()->field('id')->in($ids)->getQuery()->execute()->toArray(false);
    }

}