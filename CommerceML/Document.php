<?php

namespace Extension\Shop\CommerceML;

class Document extends Element
{
    private $classifier;

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

    /**
     * @return \Extension\Shop\CommerceML\Classifier
     */
    public function getClassifier()
    {
        if(null === $this->classifier) {
            $this->classifier = new Classifier($this->getChildElement('Классификатор'));
        }
        return $this->classifier;
    }

    /**
     *
     * @return Catalog|null
     */
    public function getCatalog()
    {
        return new Catalog($this->getChildElement('Каталог'));
    }
}