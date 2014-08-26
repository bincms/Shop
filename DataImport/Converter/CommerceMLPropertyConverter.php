<?php

namespace Extension\Shop\DataImport\Converter;

use BinCMS\DataImport\Converter\ConverterInterface;
use Extension\Shop\Document\ProductProperty;
use Extension\Shop\Document\ProductPropertyOption;

class CommerceMLPropertyConverter implements ConverterInterface
{
    /**
     * @param \Extension\Shop\CommerceML\Property $data
     * @return array
     */
    public function convert($data)
    {
        $options = [];
        $type = $this->getPropertyType($data->getType());

        if($type === ProductProperty::TYPE_DIRECTORY) {
            foreach($data->getOptions() as $option) {
                if(null !== $option->getId() && null !== $option->getValue()) {
                    $options[$option->getId()] = new ProductPropertyOption($option->getValue());
                }
            }
        }

        return [
            'externalId' => $data->getId(),
            'title' => $data->getTitle(),
            'type' => $type,
            'options' => $options
        ];
    }

    private function getPropertyType($type)
    {
        switch($type) {
            case 'Справочник':
                return ProductProperty::TYPE_DIRECTORY;
            break;
            case 'Число':
                return ProductProperty::TYPE_INT;
            break;
            default:
                return ProductProperty::TYPE_INT;
            break;
        }
    }
}