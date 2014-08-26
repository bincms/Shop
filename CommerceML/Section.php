<?php

namespace Extension\Shop\CommerceML;

class Section extends Element
{

    public function getChild($nodeName, $className)
    {
        $node = $this->getChildElement($nodeName);
        if (null === $node)
        {
            return null;
        }

        $registry = $this->doc->getRegistry();
        $element = $registry->get($node);

        if (null === $element)
        {
            $className = '\Veligost\CommerceML\\' . $className;
            $element = new $className($node, $this->doc);
            $registry->register($element);
        }

        return $element;
    }

} 