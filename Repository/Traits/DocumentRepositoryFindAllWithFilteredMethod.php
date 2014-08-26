<?php

namespace Extension\Shop\Repository\Traits;

use BinCMS\FilterBuilder\FilterBuilder;
use BinCMS\Pagination\PaginationDataBaseInterface;

trait DocumentRepositoryFindAllWithFilteredMethod
{
    /**
     * @param FilterBuilder $filterBuilder
     * @param PaginationDataBaseInterface $pagination
     * @param boolean $single
     * @return mixed|null
     */
    public function findAllWithFiltered(FilterBuilder $filterBuilder = null, PaginationDataBaseInterface $pagination = null,
                                        $single = false, $limit = false)
    {
        /** @var \Doctrine\MongoDB\Query\Builder $queryBuilder */
        $queryBuilder = $this->createQueryBuilder();

        if(null !== $filterBuilder) {
            foreach($filterBuilder->getCurrentFilters() as $filter) {
                $filter->getType()->setQueryBuilder($queryBuilder);
                $filter->apply();
            }
        }

        if(null !== $pagination) {

            $queryBuilderCount = clone $queryBuilder;

            $count = $queryBuilderCount->count()
                ->getQuery()
                ->execute();

            $pagination->setQueryBuilder($queryBuilder);
            $pagination->apply($count);
        }

        if(false !== $limit) {
            $queryBuilder->limit($limit);
        }

        $cursor = $queryBuilder->getQuery()->execute();

        if($single) {
            return $cursor->getSingleResult();
        }

        if(null !== $pagination) {
            return [
                'items' => $cursor->toArray(false),
                'pagination' => $pagination
            ];
        }

        return $cursor->toArray(false);
    }
} 