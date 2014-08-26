<?php

namespace Extension\Shop\CommerceML;


class WarehouseAvailability extends Element
{
    /**
     * @return null|string
     */
    public function getWarehouseId()
    {
        return $this->getChildValue('ИдСклада');
    }

    /**
     * @return float
     */
    public function getAvailability()
    {
        return intval($this->getChildValue('Количество', 0));
    }
} 