<?php

namespace Extension\Shop\CommerceML;

class ElementGroups extends ElementTree
{
    protected function getChildClassName()
    {
        return 'Group';
    }

    protected function getChildNodeName()
    {
        return 'Группа';
    }
}