<?php

namespace Fx\SchoolBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Monto extends Constraint
{
    public $message    = 'Este valor no es válido.';
    public $minMessage = 'Este valor debería ser {{ limit }} o más.';
    public $maxMessage = 'Este valor debería ser {{ limit }} o menos.';
    public $min        = '0.00';
    public $max;


    public function validatedBy()
    {
        return 'monto';
    }
}
