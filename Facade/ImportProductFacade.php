<?php

namespace Extension\Shop\Facade;

class ImportProductFacade extends ImportFacadeAbstract
{
    protected function getData(ImportFacadeContextInterface $context)
    {
        return $context->getProducts();
    }
}