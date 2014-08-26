<?php

namespace Extension\Shop\SphinxXml;

class SphinxDocument
{
    private $id;
    private $content;
    private $description;
    private $type;

    public function __construct($id, $content, $description, $type)
    {
        $this->id = $id;
        $this->content = $content;
        $this->description = $description;
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

}