<?php

namespace Extension\Shop\CommerceML;

abstract class ElementTree extends Element
{
    public function getChildren()
    {
        $children = [];

        foreach ($this->getElement()->childNodes as $node) {
            if ($node->nodeName != $this->getChildNodeName()) {
                continue;
            }

            $className = 'Extension\\Shop\\CommerceML\\' . $this->getChildClassName();
            $children[] = new $className($node);
        }

        return $children;
    }

    abstract protected function getChildClassName();

    abstract protected function getChildNodeName();
}