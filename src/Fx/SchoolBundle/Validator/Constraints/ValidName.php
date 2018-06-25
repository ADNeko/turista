<?php

namespace Fx\SchoolBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/
class ValidName extends Constraint
{
    public $message = "Escriba un nombre válido.";

    public function validatedBy()
    {
        return 'valid_name';
    }
}