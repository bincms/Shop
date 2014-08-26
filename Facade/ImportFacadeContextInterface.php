<?php

namespace Extension\Shop\Facade;

interface ImportFacadeContextInterface
{
    public function getProducts();
    public function getCategories();
    public function getOffers();
    public function getProperties();
    public function getWarehouses();
}