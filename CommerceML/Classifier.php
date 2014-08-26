<?php

namespace Extension\Shop\CommerceML;

class Classifier extends Element
{
    private $groups;

    private $properties;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getChildValue('Наименование', '');
    }

    /**
     * @return Group[]
     */
    public function getGroups()
    {
        if(null === $this->groups) {
            $this->groups = new ElementGroups($this->getChildElement('Группы'));
        }

        return $this->groups->getChildren();
    }

    /**
     * @return Property[]
     */
    public function getProperties()
    {
        if(null === $this->properties) {
            $this->properties = new Properties($this->getChildElement('Свойства'));
        }

        return $this->properties->getChildren();
    }

}