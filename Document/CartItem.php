<?php

namespace Extension\Shop\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class CartItem
{
    /**
     * @var \Extension\Shop\Document\Product
     * @MongoDB\ReferenceOne(targetDocument="Extension\Shop\Document\Product")
     */
    protected $product;

    /**
     * @MongoDB\Int()
     */
    protected $quantity;

    /**
     * @return \Extension\Shop\Document\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getTotalPrice()
    {
        return $this->quantity * $this->product->getPrice()->getRetail();
    }
} 