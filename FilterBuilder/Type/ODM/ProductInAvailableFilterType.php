<?php

namespace Extension\Shop\FilterBuilder\Type\ODM;

use BinCMS\FilterBuilder\FilterType\ODMFilterType;
use Symfony\Component\HttpFoundation\Request;

class ProductInAvailableFilterType extends ODMFilterType
{
    private $value;

    public function apply()
    {
        $this->queryBuilder->where("function() {
            var total = 0;
            for(i in this.availability) {
                total += this.availability[i];
            }
            return total > 0;
        }");
    }

    public function bindRequest(Request $request, $filterName, $columnName)
    {
        $this->value = filter_var($request->get($filterName), \FILTER_VALIDATE_BOOLEAN);
    }
}