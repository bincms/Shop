<?php

namespace Extension\Shop\DataImport\Converter;

use BinCMS\DataImport\Converter\ConverterInterface;
use Extension\Shop\Document\ProductPrice;
use Extension\Shop\Repository\Interfaces\WarehouseRepositoryInterface;

class CommerceMLOfferConverter implements ConverterInterface
{

    /**
     * @var \Extension\Shop\Repository\Interfaces\WarehouseRepositoryInterface
     */
    private $warehouseRepository;

    /**
     * @var array
     */
    private $warehousesMap;

    public function __construct(WarehouseRepositoryInterface $warehouseRepository)
    {

        $this->warehouseRepository = $warehouseRepository;
    }

    /**
     * @return array
     */
    private function getWarehousesMap()
    {
        if(null === $this->warehousesMap) {

            $this->warehousesMap = [];
            foreach($this->warehouseRepository->findAll() as $warehouse) {
                $this->warehousesMap[$warehouse->getExternalId()] = $warehouse->getId();
            }
        }
        return $this->warehousesMap;
    }

    /**
     * @param $externalId
     * @return bool|string
     */
    private function getWarehouseIdByExternalId($externalId)
    {
        $warehouseMap = $this->getWarehousesMap();
        return isset($warehouseMap[$externalId]) ? $warehouseMap[$externalId] : false;
    }

    /**
     * @param \Extension\Shop\CommerceML\ProductOffer $data
     * @return array
     */
    public function convert($data)
    {
        $prices = $data->getPrices();

        $warehousesAvailability = [];
        foreach($data->getWarehousesAvailability() as $warehouse) {
            if(($warehouseId = $this->getWarehouseIdByExternalId($warehouse->getWarehouseId()))) {
                $warehousesAvailability[$warehouseId] = $warehouse->getAvailability();
            }
        }

        $price = 0;
        if(sizeof($prices) > 0) {
            $price = $prices[0]->getValue();
        }

        return [
            'externalId' => $data->getId(),
            'price' => new ProductPrice($price),
            'availability' => $warehousesAvailability
        ];
    }
}