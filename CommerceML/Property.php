<?php

namespace Extension\Shop\CommerceML;

class Property extends Element
{
    private $optionsMeaning;

    /**
     * @return null|string
     */
    public function getId()
    {
        return $this->getChildValue('Ид');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getChildValue('Наименование', '');
    }

    /**
     * @return null|string
     */
    public function getType()
    {
        return $this->getChildValue('ТипЗначений');
    }

    /**
     * @return OptionMeaning[]
     */
    public function getOptions()
    {
        if(null === $this->optionsMeaning) {
            $this->optionsMeaning = new OptionsMeaning($this->getChildElement('ВариантыЗначений'));
        }

        return $this->optionsMeaning->getChildren();
    }
} 