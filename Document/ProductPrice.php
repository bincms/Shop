<?php

namespace Extension\Shop\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class ProductPrice
{
    /**
     * @MongoDB\Float
     */
    protected $trade;

    /**
     * @MongoDB\Float
     */
    protected $retail;

    /**
     * @MongoDB\Float
     */
    protected $savings;

    /**
     * @MongoDB\Int
     */
    protected $pctSavings;

    function __construct($retail, $trade = 0, $savings = 0, $pctSavings = 0)
    {
        $this->retail = $retail;
        $this->savings = $savings;
        $this->trade = $trade;
        $this->pctSavings = $pctSavings;
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
    public function getTrade()
    {
        return $this->trade;
    }

    /**
     * @param mixed $trade
     */
    public function setTrade($trade)
    {
        $this->trade = $trade;
    }

    /**
     * @return mixed
     */
    public function getRetail()
    {
        return $this->retail;
    }

    /**
     * @param mixed $retail
     */
    public function setRetail($retail)
    {
        $this->retail = $retail;
    }

    /**
     * @return mixed
     */
    public function getSavings()
    {
        return $this->savings;
    }

    /**
     * @param mixed $savings
     */
    public function setSavings($savings)
    {
        $this->savings = $savings;
    }

    /**
     * @return mixed
     */
    public function getPctSavings()
    {
        return $this->pctSavings;
    }

    /**
     * @param mixed $pctSavings
     */
    public function setPctSavings($pctSavings)
    {
        $this->pctSavings = $pctSavings;
    }

} 