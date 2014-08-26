<?php

namespace Extension\Shop\Form;

use BinCMS\Form\BaseForm;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class ProductForm extends BaseForm
{
    public $sku;
    public $title;
    /**
     * @var PriceForm
     */
    public $price;
    public $description;
    public $availability;
    public $isLeader;
    public $isNew;
    public $categoryId;

    public function __construct()
    {
        $this->price = new PriceForm();
    }
} 