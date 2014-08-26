<?php

namespace Extension\Shop\Repository\Traits;

use BinCMS\FilterBuilder\FilterBuilder;
use BinCMS\Pagination\PaginationDataBaseInterface;

trait MaterializedPathRepositoryFindAllWithFilteredMethod
{
    /**
     * @param FilterBuilder $filterBuilder
     * @param PaginationDataBaseInterface $pagination
     * @return mixed|null
     */
    public function findAllWithFilter($node = null, FilterBuilder $filterBuilder = null, PaginationDataBaseInterface $pagination = null, $direct = true)
    {
        /** @var \Doctrine\MongoDB\Query\Builder $queryBuilder */
        $queryBuilder = $this->getChildrenQueryBuilder($node, $direct);

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

        $result = $queryBuilder->getQuery()->execute()->toArray(false);

        if(null !== $pagination) {
            return [
                'items' => $result,
                'pagination' => $pagination
            ];
        }

        return $result;
    }
} 