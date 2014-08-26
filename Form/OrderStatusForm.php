<?php

namespace Extension\Shop\Form;

use BinCMS\Form\BaseForm;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class OrderStatusForm extends BaseForm
{
    public $title;

    /**
     * @param ClassMetadata $metadata
     * @return void
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('title', new Assert\NotBlank());
    }

} 