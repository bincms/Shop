<?php

namespace Extension\Shop\Document;

use BinCMS\Document\Shedule;
use BinCMS\DocumentTrait\DocumentLocationTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="Extension\Shop\Repository\ShopRepository")
 */
class Shop
{
    use DocumentLocationTrait;

    /**
     * @MongoDB\Id()
     */
    protected $id;

    /**
     * @MongoDB\String()
     */
    protected $title;

    /**
     * @MongoDB\EmbedOne(targetDocument="BinCMS\Document\Address")
     */
    protected $address;

    /**
     * @MongoDB\String()
     */
    protected $phone;

    /**
     * @MongoDB\String()
     */
    protected $email;

    /**
     * @MongoDB\String()
     */
    protected $skype;

    /**
     * @MongoDB\EmbedOne(targetDocument="BinCMS\Document\Shedule")
     */
    protected $shedule;

    /**
     * @MongoDB\ReferenceMany(targetDocument="\Extension\Shop\Document\Warehouse")
     */
    protected $warehouses;

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
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
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
     * @return mixed
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @param mixed $skype
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
    }

    /**
     * @return mixed
     */
    public function getShedule()
    {
        return $this->shedule;
    }

    /**
     * @param mixed $shedule
     */
    public function setShedule(Shedule $shedule)
    {
        $this->shedule = $shedule;
    }

    /**
     * @return array
     */
    public function getWarehouses()
    {
        return $this->warehouses;
    }

    public function setWarehouses($warehouses)
    {
        $this->warehouses = $warehouses;
    }

    public function addWarehouse(Warehouse $warehouse)
    {
        $this->warehouses[] = $warehouse;
    }
} 