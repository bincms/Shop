<?php

namespace Extension\Shop\Document;

use BinCMS\Document\User;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="Extension\Shop\Repository\CartRepository")
 */
class Cart
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="BinCMS\Document\User")
     */
    protected $user;

    /**
     * @MongoDB\EmbedMany(targetDocument="CartItem")
     */
    protected $items;

    public function __construct()
    {
        $this->setItems([]);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \BinCMS\Document\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \BinCMS\Document\User $user
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * @return \Extension\Shop\Document\CartItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param \Extension\Shop\Document\CartItem[] $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @param \Extension\Shop\Document\CartItem $cartItem
     */
    public function addItem(CartItem $cartItem)
    {
        $this->items[] = $cartItem;
    }

    public function getTotalPrice()
    {
        $totalPrice = 0;

        if(!empty($this->items)) {
            /** @var \Extension\Shop\Document\CartItem $item */
            foreach($this->items as $item) {
                $totalPrice += $item->getTotalPrice();
            }
        }

        return $totalPrice;
    }


} 