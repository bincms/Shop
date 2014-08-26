<?php

namespace Extension\Shop\CommerceML;

class PropertyValue extends Element
{
    /**
     * @return null|string
     */
    public function getId()
    {
        return $this->getChildValue('Ид');
    }

    /**
     * @return null|string
     */
    public function getValue()
    {
        return $this->getChildValue('Значение');
    }
}