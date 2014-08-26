<?php

namespace Extension\Shop\CommerceML;

class Product extends Element
{
    private $properties;

    /**
     * @return null|string
     */
    public function getId()
    {
        return $this->getChildValue('Ид');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getChildValue('Наименование', '');
    }

    /**
     * @return string
     */
    public function getFullTitle()
    {
        return $this->getChildValue('ПолноеНаименование', '');
    }

    public function getArticle()
    {
        return $this->getChildValue('Артикул', '');
    }

    /**
     * @return GroupElement
     */
    public function getGroup()
    {
        return new GroupElement($this->getChildElement('Группы'));
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->getChildValue('Описание', '');
    }

    public function getImage()
    {
        return $this->getChildValue('Картинка');
    }

    /**
     * @return PropertyValue[]
     */
    public function getProperties()
    {
        if(null === $this->properties) {
            $this->properties = new PropertiesValues($this->getChildElement('ЗначенияСвойств'));
        }

        return $this->properties->getChildren();
    }
} 