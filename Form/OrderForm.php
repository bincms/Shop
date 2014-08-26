<?php

namespace Extension\Shop\Form;

use BinCMS\Form\AddressForm;
use BinCMS\Form\BaseForm;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class OrderForm extends BaseForm
{
    public $deliveryType;
    public $deliveryId;
    /**
     * @var AddressForm
     */
    public $address;
    public $shopId;
    public $items;
    public $comment;

    public function __construct()
    {
        $this->address = new AddressForm();
    }

    /**
     * @param ClassMetadata $metadata
     * @return void
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new Assert\Callback('validate'));
    }

    public function validate(ExecutionContextInterface $context)
    {
        if($this->deliveryType==0 && $this->deliveryId === null) {
            $context->addViolationAt('deliveryId', 'Not select delivery location');
        } else if($this->deliveryType==1 && $this->shopId === null) {
            $context->addViolationAt('shopId', 'Not select delivery location');
        }
    }
}