<?php

namespace Extension\Shop\Facade;

class ImportPropertyFacade extends ImportFacadeAbstract
{
    protected function getData(ImportFacadeContextInterface $context)
    {
        return $context->getProperties();
    }
}