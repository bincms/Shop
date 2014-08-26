<?php

namespace Extension\Shop\Facade;

class ImportOfferFacade extends ImportFacadeAbstract
{
    protected function getData(ImportFacadeContextInterface $context)
    {
        return $context->getOffers();
    }
}