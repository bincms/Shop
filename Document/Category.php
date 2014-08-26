<?php

namespace Extension\Shop\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @MongoDB\Document(repositoryClass="Extension\Shop\Repository\CategoryRepository")
 * @Gedmo\Tree(type="materializedPath", activateLocking=true)
 */
class Category
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $externalId;

    /**
     * @MongoDB\String
     * @Gedmo\TreePathSource
     */
    protected $title;

    /**
     * @MongoDB\String
     * @Gedmo\TreePath(separator="|", appendId=true)
     */
    protected $path;

    /**
     * @Gedmo\TreeParent
     * @MongoDB\ReferenceOne(targetDocument="Category")
     */
    protected $parent;

    /**
     * @Gedmo\TreeLevel
     * @MongoDB\Field(type="int")
     */
    protected $level;

    /**
     * @Gedmo\TreeLockTime
     * @MongoDB\Field(type="date")
     */
    protected $lockTime;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Category", mappedBy="parent")
     */
    protected $children;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param mixed $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getArrayPath()
    {
        $result = [];

        $items = explode('|', $this->getPath());

        foreach($items as $item) {
            if($item !== '') {
                $parts = explode('-', $item);
                if(sizeof($parts) == 2) {
                    $result[] = ['id' => $parts[1], 'title' => $parts[0]];
                }
            }
        }

        return $result;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getLockTime()
    {
        return $this->lockTime;
    }

    /**
     * @param mixed $lockTime
     */
    public function setLockTime($lockTime)
    {
        $this->lockTime = $lockTime;
    }

    /**
     * @return Category[]
     */
    public function getChildren()
    {
        return $this->children;
    }

}