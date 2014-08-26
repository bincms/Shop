<?php

namespace Extension\Shop\Facade;

class ImportFacadeContext implements ImportFacadeContextInterface
{
    /**
     * @var array
     */
    private $products;
    /**
     * @var array
     */
    private $categories;
    /**
     * @var array
     */
    private $offers;
    /**
     * @var array
     */
    private $properties;
    /**
     * @var array
     */
    private $warehouses;

    public function __construct($products = [], $categories = [], $properties = [], $offers = [], $warehouses = [])
    {
        $this->products = $products;
        $this->categories = $categories;
        $this->offers = $offers;
        $this->properties = $properties;
        $this->warehouses = $warehouses;
    }

    /**
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return array
     */
    public function getOffers()
    {
        return $this->offers;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    public function getWarehouses()
    {
        return $this->warehouses;
    }
}