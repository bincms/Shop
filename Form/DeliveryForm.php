<?php

namespace Extension\Shop\Form;

use BinCMS\Form\BaseForm;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class DeliveryForm extends BaseForm
{
    public $title;

    public $description;

    public $price;

    public $enabled;

    /**
     * @param ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('title', new Assert\NotBlank());
        $metadata->addPropertyConstraint('price', new Assert\NotBlank());
    }

} 