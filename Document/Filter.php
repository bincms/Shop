<?php

namespace Extension\Shop\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation\ReferenceOne;

/**
 * @MongoDB\Document(repositoryClass="Extension\Shop\Repository\FilterRepository")
 */
class Filter
{
    /**
     * @MongoDB\Id()
     */
    protected $id;

    /**
     * @MongoDB\String()
     */
    protected $propertyId;

    /**
     * @MongoDB\String()
     */
    protected $title;

    /**
     * @MongoDB\String()
     */
    protected $categoryId;

    /**
     * @MongoDB\EmbedMany(targetDocument="Extension\Shop\Document\FilterValue")
     */
    protected $values = [];

    public function __construct()
    {
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
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param mixed $categoryId
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param mixed $properties
     */
    public function addValue(FilterValue $filterValue)
    {
        if(empty($filterValue->getValue())) {
            return;
        }

        $isExists = false;
        foreach($this->values as $value) {
            if($value->getValue() == $filterValue->getValue()) {
                $isExists = true;
                break;
            }
        }

        if($isExists) {
            return;
        }

        $this->values[] = $filterValue;
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
     * @param mixed $values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }

    /**
     * @return mixed
     */
    public function getPropertyId()
    {
        return $this->propertyId;
    }

    /**
     * @param mixed $propertyId
     */
    public function setPropertyId($propertyId)
    {
        $this->propertyId = $propertyId;
    }

}