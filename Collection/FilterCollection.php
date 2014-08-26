<?php


namespace Extension\Shop\Collection;

use Doctrine\Common\Collections\ArrayCollection;

class FilterCollection extends ArrayCollection
{
    public function getFilterByPropertyId($propertyId)
    {

        foreach($this as $filter) {
            if($filter->getPropertyId() === $propertyId) {
                return $filter;
            }
        }


        return null;
    }


} 