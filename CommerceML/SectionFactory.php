<?php

namespace Extension\Shop\CommerceML;

class SectionFactory
{

    public function create($class, $element)
    {
        if($element === null) {
            return null;
        }

        $className = 'Extension\\Shop\\CommerceML\\Section\\'.$class;

        return new $className($element);

    }

} 