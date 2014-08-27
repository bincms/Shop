<?php

namespace Extension\Shop\Form;

use BinCMS\Form\AddressForm;
use BinCMS\Form\BaseForm;
use BinCMS\Form\LocationForm;

class ShopForm extends BaseForm
{
    public $title;
    public $shedule;
    public $warehouses;
    public $address;
    public $location;

    public function __construct()
    {
        $this->shedule = new SheduleForm();
        $this->address = new AddressForm();
        $this->location = new LocationForm();
    }
} 