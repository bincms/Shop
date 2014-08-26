<?php

namespace Extension\Shop\CommerceML;

class Products extends ElementTree
{
    protected function getChildClassName()
    {
        return 'Product';
    }

    protected function getChildNodeName()
    {
        return 'Товар';
    }
}