<?php

namespace Extension\Shop\CommerceML;

class ProductOfferPrices extends ElementTree
{
    protected function getChildClassName()
    {
        return 'ProductOfferPrice';
    }

    protected function getChildNodeName()
    {
        return 'Цена';
    }
}