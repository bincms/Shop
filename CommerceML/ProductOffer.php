<?php

namespace Extension\Shop\CommerceML;

class ProductOffer extends Element
{
    /**
     * @return null|string
     */
    public function getId()
    {
        return $this->getChildValue('Ид');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return  $this->getChildValue('Наименование', '');
    }

    /**
     * @return WarehouseAvailability[]
     */
    public function getWarehousesAvailability()
    {
        $warehousesAvailability = new WarehousesAvailability($this->getChildElement('КоличествоНаСкладах'));
        return $warehousesAvailability->getChildren();
    }

    /**
     * @return ProductOfferPrice[]
     */
    public function getPrices()
    {
        $productOfferPrices = new ProductOfferPrices($this->getChildElement('Цены'));
        return $productOfferPrices->getChildren();
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return intval($this->getChildValue('Количество', 0));
    }
} 