<?php

namespace Extension\Shop\Converter;

use BinCMS\Converter\ConverterEventInterface;
use BinCMS\Converter\ConverterInterface;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Document\ProductProperty;
use Extension\Shop\Document\ProductPropertyValue;

class ProductPropertyValueConverter implements ConverterInterface
{
    public function convert($value, ConverterService $converterService, ConverterEventInterface $event)
    {
        return $this->convertValue($value, $converterService, $event);
    }

    private function convertValue(ProductPropertyValue $productPropertyValue, ConverterService $converterService,
                                  ConverterEventInterface $event)
    {
        $property = $productPropertyValue->getProperty();

        $value = '';
        if($property->getType() == ProductProperty::TYPE_DIRECTORY) {
            $options = $property->getOptions();
            if(isset($options[$productPropertyValue->getValue()])) {
                $value = $options[$productPropertyValue->getValue()]->getValue();
            }
        } else {
            $value = $productPropertyValue->getValue();
        }

        return [
            'title' => $property->getTitle(),
            'value' => $value
        ];
    }
}