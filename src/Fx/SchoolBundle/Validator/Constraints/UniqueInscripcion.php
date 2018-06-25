<?php

namespace Fx\SchoolBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueInscripcion extends Constraint
{
    public function validatedBy()
    {
        return 'unique_inscripcion';
    }


    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}