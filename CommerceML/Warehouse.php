<?php

namespace Extension\Shop\CommerceML;

class Warehouse extends Element
{
    public function getId()
    {
        return $this->getChildValue('ИдСклада');
    }

    public function getTitle()
    {
        return $this->getChildValue('Наименование');
    }
} 