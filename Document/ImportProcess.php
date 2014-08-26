<?php

namespace Extension\Shop\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @MongoDB\Document(repositoryClass="Extension\Shop\Repository\ImportProcessRepository")
 */
class ImportProcess
{
    const ST_IN_PROGRESS = 1;
    const ST_FINISHED = 2;
    const ST_ERROR = 3;

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Int
     *
     * 1 - in progress, 2 - finished, 3 - error
     */
    protected $status;

    /**
     * @MongoDB\String
     */
    protected $text;

    /**
     * @MongoDB\Date
     * @Gedmo\Timestampable(on="create")
     */
    protected $started;

    /**
     * @MongoDB\Date
     */
    protected $finished;

    public function __construct($status = self::ST_IN_PROGRESS)
    {
        $this->status = $status;
    }

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
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * @param mixed $started
     */
    public function setStarted($started)
    {
        $this->started = $started;
    }

    /**
     * @return mixed
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * @param mixed $finished
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;
    }

} 