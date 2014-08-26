<?php

namespace Extension\Shop\DataImport\Converter;

use BinCMS\DataImport\Converter\ConverterInterface;

class CommerceMLWarehouseConverter implements ConverterInterface
{
    /**
     * @param \Extension\Shop\CommerceML\Warehouse $data
     */
    public function convert($data)
    {
        return [
            'externalId' => $data->getId(),
            'title' => $data->getTitle()
        ];
    }
}