<?php

namespace Extension\Shop\Facade;

class ImportWarehouseFacade extends ImportFacadeAbstract
{
    protected function getData(ImportFacadeContextInterface $context)
    {
        return $context->getWarehouses();
    }
} 