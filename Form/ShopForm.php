<?php

namespace Extension\Shop\Form;

use BinCMS\Form\BaseForm;

class ShopForm extends BaseForm
{
    public $title;
    public $country;
    public $city;
    public $street;
    public $house;
    public $lat;
    public $lng;
    public $shedule;
    public $warehouses;

    public function __construct()
    {
        $this->shedule = new SheduleForm();
    }


} 