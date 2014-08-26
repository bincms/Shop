<?php

namespace Extension\Shop\CommerceML;

class Offers extends Element
{
    public function __construct(\DOMDocument $document)
    {
        parent::__construct($document->firstChild);
    }

    public function getVersion()
    {
        return $this->getElement()->getAttribute('ВерсияСхемы');
    }

    /**
     *
     * @return \DateTime
     */
    public function getTimeCreated()
    {
        return new \DateTime($this->getElement()->getAttribute('ДатаФормирования'));
    }

    public function getPackageOffer()
    {
        return new PackageOffers($this->getChildElement('ПакетПредложений'));
    }
} 