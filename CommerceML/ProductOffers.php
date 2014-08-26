<?php

namespace Extension\Shop\CommerceML;

class ProductOffers extends ElementTree
{
    protected function getChildClassName()
    {
        return 'ProductOffer';
    }

    protected function getChildNodeName()
    {
        return 'Предложение';
    }
}