<?php

namespace Extension\Shop\CommerceML;

class ProductOfferPrice extends Element
{
    /**
     * @return string
     */
    public function getDisplay()
    {
        return $this->getChildValue('Представление', '');
    }

    /**
     * @return null|string
     */
    public function getTypeId()
    {
        return $this->getChildValue('ИдТипаЦены');
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return floatval($this->getChildValue('ЦенаЗаЕдиницу', 0));
    }

    /**
     * @return int
     */
    public function getCurrency()
    {
        return intval($this->getChildValue('Валюта', 0));
    }
} 