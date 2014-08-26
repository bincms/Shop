<?php

namespace Extension\Shop\CommerceML;

class OptionMeaning extends Element
{
    /**
     * @return null|string
     */
    public function getId()
    {
        return $this->getChildValue('ИдЗначения');
    }

    /**
     * @return null|string
     */
    public function getValue()
    {
        return $this->getChildValue('Значение');
    }
} 