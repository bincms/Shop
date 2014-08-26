<?php

namespace Extension\Shop\Repository;

use BinCMS\RepositoryTrait\ExtendRepositoryTrait;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Extension\Shop\Repository\Interfaces\CartRepositoryInterface;
use Extension\User\Document\User;

class CartRepository extends DocumentRepository implements CartRepositoryInterface
{
    use \BinCMS\RepositoryTrait\ExtendRepositoryTrait;

    public function findByUserOrId($value)
    {
        if($value instanceof User) {
            return $this->createQueryBuilder()->field('user')->references($value)->getQuery()->execute()->getSingleResult();
        }
        return $this->find($value);
    }
}