<?php

namespace Fx\SchoolBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PermittedRole extends Constraint
{
    public $message = "No puede crear un usuario con rol %rol%.";


    public function validatedBy()
    {
        return 'permitted_role';
    }
}