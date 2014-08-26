<?php

namespace Extension\Shop\CommerceML;

class Group extends Element
{
    private $children;

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
     * @return Group[]
     */
    public function getChildren()
    {
        if(null === $this->children) {
            $this->children = new ElementGroups($this->getChildElement('Группы'));
        }
        return $this->children->getChildren();
    }
}