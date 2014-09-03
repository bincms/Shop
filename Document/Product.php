<?php

namespace Extension\Shop\Document;

use BinCMS\DocumentTrait\DocumentExternalTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @MongoDB\Document(repositoryClass="Extension\Shop\Repository\ProductRepository")
 */
class Product
{
    use DocumentExternalTrait;

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="BinCMS\Document\Image")
     */
    protected $image;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Category", simple=true)
     */
    protected $category;

    /**
     * @MongoDB\String
     */
    protected $sku;

    /**
     * @MongoDB\String
     */
    protected $title;

    /**
     * @MongoDB\String
     */
    protected $description;

    /**
     * @MongoDB\EmbedOne(targetDocument="ProductPrice")
     */
    protected $price;

    /**
     * @MongoDB\Hash()
     */
    protected $availability;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Manufacturer", simple=true)
     */
    protected $manufacturer;

    /**
     * @MongoDB\Boolean
     */
    protected $isNew;

    /**
     * @MongoDB\Boolean
     */
    protected $isLeader;

    /**
     * @MongoDB\EmbedMany(targetDocument="ProductPropertyValue")
     */
    protected $properties;

    /**
     * @MongoDB\String()
     */
    protected $unit;

    public function __construct()
    {
        $this->availability = [];
        $this->isLeader = false;
        $this->isNew = false;
        $this->price = new ProductPrice(0);
        $this->properties = new ArrayCollection();
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
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param mixed $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $name
     */
    public function setTitle($name)
    {
        $this->title = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return ProductPrice
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice(ProductPrice $price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * @param mixed $availability
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;
    }

    public function getTotalAvailability()
    {
        $totalAvailability = 0;
        foreach($this->getAvailability() as $value) {
            $totalAvailability += $value;
        }
        return $totalAvailability;
    }

    /**
     * @param mixed $manufacturer
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;
    }

    /**
     * @return mixed
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * @param mixed $isNew
     */
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;
    }

    /**
     * @return mixed
     */
    public function getIsLeader()
    {
        return $this->isLeader;
    }

    /**
     * @param mixed $isLeader
     */
    public function setIsLeader($isLeader)
    {
        $this->isLeader = $isLeader;
    }

    /**
     * @return mixed
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * @return mixed
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param mixed $properties
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
    }

    /**
     * @return mixed
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param mixed $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

}