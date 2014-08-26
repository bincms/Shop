<?php

namespace Extension\Shop\Facade;

interface ImportFacadeInterface
{
    public function process(ImportFacadeContextInterface $document, $truncate);
} 