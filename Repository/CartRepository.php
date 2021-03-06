<?php

namespace Extension\Shop\Repository;

use BinCMS\Document\User;
use BinCMS\RepositoryTrait\RepositoryExtendTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Extension\Shop\Repository\Interfaces\CartRepositoryInterface;

class CartRepository extends DocumentRepository implements CartRepositoryInterface
{
    use RepositoryExtendTrait;

    public function findByUserOrId($value)
    {
        if($value instanceof User) {
            return $this->createQueryBuilder()->field('user')->references($value)->getQuery()->execute()->getSingleResult();
        }
        return $this->find($value);
    }
}