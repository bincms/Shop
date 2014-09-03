<?php

namespace Extension\Shop\FilterBuilder\Type\ODM;

use BinCMS\FilterBuilder\FilterType\ODMFilterType;
use Symfony\Component\HttpFoundation\Request;

class ProductInAvailableFilterType extends ODMFilterType
{
    private $isEnabled;

    public function apply()
    {
        if ($this->isEnabled) {
            $queryBuilder = $this->queryBuilder;

            $queryBuilder->addAnd($queryBuilder->expr()->where("function() {
                var total = 0;
                for(i in this.availability) {
                    total += this.availability[i];
                }
                return total > 0;
                }")
            );
        }
    }

    public function bindRequest(Request $request, $filterName, $columnName)
    {
        $this->isEnabled = filter_var($request->get($filterName), \FILTER_VALIDATE_BOOLEAN);
    }
}