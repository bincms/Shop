<?php

namespace Extension\Shop\FilterBuilder\Type\ODM;

use BinCMS\FilterBuilder\FilterType\ODMFilterType;
use Symfony\Component\HttpFoundation\Request;

class ProductFilterType extends ODMFilterType
{
    private $value;

    public function apply()
    {
        if(null !== $this->value && !empty($this->value)) {

            $queryBuilder = $this->queryBuilder;
            $expr = $queryBuilder->expr();
            $filters = explode(';', $this->value);

            foreach($filters as $filter) {
                list($id, $valueData) = explode(':', $filter);
                $values = explode(',', $valueData);

                $valueExpr = $queryBuilder->expr();

                foreach($values as $value) {
                    $valueExpr->field('property.$id')->equals(new \MongoId($id))
                        ->field('value')->equals($value);
                }

                $queryBuilder->addAnd($queryBuilder->expr()->field('properties')->elemMatch(
                    $valueExpr
                ));
            }
        }
    }

    public function bindRequest(Request $request, $filterName, $columnName)
    {
        $this->value = $request->get($filterName);
    }
}