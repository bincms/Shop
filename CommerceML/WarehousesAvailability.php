<?php

namespace Extension\Shop\CommerceML;

class WarehousesAvailability extends ElementTree
{
    protected function getChildClassName()
    {
        return 'WarehouseAvailability';
    }

    protected function getChildNodeName()
    {
        return 'КоличествоНаСкладе';
    }
}