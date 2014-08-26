<?php

namespace Extension\Shop\CommerceML;

class OptionsMeaning extends ElementTree
{
    protected function getChildClassName()
    {
        return 'OptionMeaning';
    }

    protected function getChildNodeName()
    {
        return 'Справочник';
    }
}