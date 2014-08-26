<?php

namespace Extension\Shop\CommerceML;

class Properties extends ElementTree
{
    protected function getChildClassName()
    {
        return 'Property';
    }

    protected function getChildNodeName()
    {
        return 'Свойство';
    }
}