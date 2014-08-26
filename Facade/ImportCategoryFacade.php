<?php

namespace Extension\Shop\Facade;

class ImportCategoryFacade extends ImportFacadeAbstract
{
    protected function getData(ImportFacadeContextInterface $context)
    {
        return $context->getCategories();
    }
}