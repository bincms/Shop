<?php

namespace Extension\Shop\Converter;

use BinCMS\Converter\ConverterEventInterface;
use BinCMS\Converter\ConverterInterface;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Document\Category;

class CategoryConverter implements ConverterInterface
{
    public function convert($value, ConverterService $converterService, ConverterEventInterface $event)
    {
        return $this->convertValue($value, $converterService, $event);
    }

    private function convertValue(Category $category, ConverterService $converterService, ConverterEventInterface $event)
    {
        $result = [
            'id' => $category->getId(),
            'title' => $category->getTitle(),
            'paths' => $category->getArrayPath(),
            'hasChildren' => sizeof($category->getChildren()) > 0
        ];

        if($event->isDetailed()) {
            $result['children'] = $converterService->convert($category->getChildren());
            $result['parent'] = $converterService->convert($category->getParent());
        }

        return $result;
    }
}