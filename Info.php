<?php

namespace Extension\Shop;

use BinCMS\ExtensionInfoInterface;

class Info implements ExtensionInfoInterface
{

    public function getVersion()
    {
        return version_compare(1, 0);
    }

    public function getTitle()
    {
        return 'Магазин';
    }

    public function getDependencies()
    {
        return [];
    }
}