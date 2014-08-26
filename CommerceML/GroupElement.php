<?php

namespace Extension\Shop\CommerceML;

class GroupElement extends Element
{
    /**
     * @return null|string
     */
    public function getId()
    {
        return $this->getChildValue('Ид');
    }
} 