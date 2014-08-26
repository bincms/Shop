<?php

namespace Extension\Shop\Document;

use BinCMS\Document\Address;
use Extension\User\Document\User;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @MongoDB\Document(repositoryClass="Extension\Shop\Repository\OrderRepository")
 */
class Order
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Int()
     */
    protected $number;

    /**
     * @MongoDB\Date
     * @Gedmo\Timestampable(on="create")
     */
    protected $created;

    /**
     * @MongoDB\Date
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated;

    /**
     * @MongoDB\Date()
     */
    protected $reserve;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Extension\User\Document\User", simple=true)
     */
    protected $customer;

    /**
     * @MongoDB\String
     */
    protected $email;

    /**
     * @MongoDB\ReferenceOne(targetDocument="OrderStatus", simple=true)
     */
    protected $status;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Delivery", simple=true)
     */
    protected $delivery;

    /**
     * @MongoDB\EmbedOne(targetDocument="BinCMS\Document\Address")
     */
    protected $address;

    /**
     * @MongoDB\EmbedMany(targetDocument="OrderProduct")
     */
    protected $products = [];

    /**
     * @MongoDB\String()
     */
    protected $comment;

    /**
     * @MongoDB\Boolean()
     */
    protected $isCanceled;

    public function __construct()
    {
        $this->setIsCanceled(false);
        $this->setReserve(new \DateTime());
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
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
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return mixed
     */
    public function getReserve()
    {
        return $this->reserve;
    }

    /**
     * @param mixed $reserve
     */
    public function setReserve($reserve)
    {
        $this->reserve = $reserve;
    }

    /**
     * @return User
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param User $customer
     */
    public function setCustomer(User $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return OrderStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return Delivery
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * @param mixed $delivery
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param mixed $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getIsCanceled()
    {
        return $this->isCanceled;
    }

    /**
     * @param mixed $isCanceled
     */
    public function setIsCanceled($isCanceled)
    {
        $this->isCanceled = $isCanceled;
    }

    public function getTotalPriceRetail()
    {
        $totalPrice = 0;
        foreach($this->getProducts() as $product) {
            $totalPrice += $product->getQuantity() * $product->getPrice()->getRetail();
        }

        return $totalPrice;
    }

    public function getDeliveryPrice()
    {
        if(($delivery = $this->getDelivery()) !== null) {
            return $delivery->getPrice();
        }

        return 0;
    }

    public function getNumberFormatted($size = 8)
    {
        return sprintf('%0'.$size.'d', $this->getNumber());
    }

}