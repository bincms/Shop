<?php

namespace Extension\Shop\CommerceML;

abstract class Element
{

    /**
     * @var \DOMElement
     */
    private $element;

    public function __construct($element)
    {
        $this->element = $element;
    }

    /**
     * @return \DOMElement
     */
    public function getElement()
    {
        return $this->element;
    }

    protected function getChildValue($name, $defaultValue = null)
    {
        if(($child = $this->getChildElement($name)) !== null) {
            return $child->nodeValue;
        }

        return $defaultValue;
    }

    protected function getChildElement($name)
    {
        if(null === $this->getElement()) {
            return null;
        }

        foreach ($this->getElement()->childNodes as $node)
        {
            /** @var \DOMElement $node */
            if ($node->nodeName == $name)
            {
                return $node;
            }
        }
        return null;
    }

}