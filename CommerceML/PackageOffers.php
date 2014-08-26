<?php

namespace Extension\Shop\CommerceML;

class PackageOffers extends Element
{
    private $productOffers;

    /**
     * @return Warehouse[]
     */
    public function getWarehouses()
    {
        $warehouses = new Warehouses($this->getChildElement('Склады'));
        return $warehouses->getChildren();
    }

    /**
     * @return ProductOffer[]
     */
    public function getProductOffers()
    {
        if(null === $this->productOffers) {
            $this->productOffers = new ProductOffers($this->getChildElement('Предложения'));
        }

        return $this->productOffers->getChildren();
    }
}