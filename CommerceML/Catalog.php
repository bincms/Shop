<?php

namespace Extension\Shop\CommerceML;

class Catalog extends Element
{
    public function isOnlyUpdate()
    {
        return $this->getElement()->getAttribute('СодержитТолькоИзменения') === 'true';
    }

    public function getProducts()
    {
        $products = new Products($this->getChildElement('Товары'));
        return $products->getChildren();
    }
}