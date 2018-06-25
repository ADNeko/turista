<?php

namespace Fx\SchoolBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidNameValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!preg_match('/^([a-zA-Z ñáéíóúüÑÁÉÍÓÚÜ]*)$/', $value, $matches)) {

            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }
    }
}