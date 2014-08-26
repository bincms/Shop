<?php

namespace Extension\Shop\CommerceML;

class Warehouses extends ElementTree
{
    protected function getChildClassName()
    {
        return 'Warehouse';
    }

    protected function getChildNodeName()
    {
        return 'Склад';
    }
}