<?php

namespace Extension\Shop\Form;

use BinCMS\Form\BaseForm;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryForm extends BaseForm
{
    public $title;
    public $parent;

    /**
     * @param ClassMetadata $metadata
     * @return void
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('title', new Assert\NotBlank());
    }
} 