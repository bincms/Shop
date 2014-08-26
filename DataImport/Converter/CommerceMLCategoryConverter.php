<?php

namespace Extension\Shop\DataImport\Converter;

use BinCMS\DataImport\Converter\ConverterInterface;

class CommerceMLCategoryConverter implements ConverterInterface
{
    public function convert($data)
    {
        return $this->recursiveConverter($data);
    }

    /**
     * @param \Extension\Shop\CommerceML\Group $data
     * @return mixed
     */
    private function recursiveConverter($data)
    {
        if(($externalId = $data->getId()) === null) {
            return false;
        }

        $children = [];

        foreach($data->getChildren() as $child) {
            if(($convertedChild = $this->recursiveConverter($child)) !== null) {
                $children[] = $convertedChild;
            }
        }

        return [
            'title' => $data->getTitle(),
            'externalId' => $externalId,
            'children' => $children
        ];
    }
}