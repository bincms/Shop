<?php

namespace Extension\Shop\CommerceML;

class PropertiesValues extends ElementTree
{

    protected function getChildClassName()
    {
        return 'PropertyValue';
    }

    protected function getChildNodeName()
    {
        return 'ЗначенияСвойства';
    }
}