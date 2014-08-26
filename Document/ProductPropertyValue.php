<?php

namespace Extension\Shop\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class ProductPropertyValue
{
    /**
     * @MongoDB\ReferenceOne(targetDocument="ProductProperty")
     */
    protected $property;

    /**
     * @MongoDB\String()
     */
    protected $value;

    /**
     * @return ProductProperty
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param mixed $idProperty
     */
    public function setProperty($idProperty)
    {
        $this->property = $idProperty;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}