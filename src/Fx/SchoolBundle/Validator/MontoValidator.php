<?php

namespace Fx\SchoolBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Fx\SchoolBundle\Entity\ReciboInscripcion;
use Fx\SchoolBundle\Validator\Constraints\Monto;
use JMS\DiExtraBundle\Annotation\Validator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @Validator("monto")
 */
class MontoValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Monto) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\Monto');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string)$value;

//        if (!preg_match('/^\d+((\.|,)\d\d)?$/', $value)) {
//            $this->context->buildViolation("Este no es un monto válido.")
//                ->addViolation();
//        }

        $min = $constraint->min;
        $max = $constraint->max;

        if (null !== $constraint->max && bccomp($value, $max) > 0) {
            $this->context->buildViolation($constraint->maxMessage)
                ->setParameter('{{ limit }}', $max)
                ->addViolation();

            return;
        }

        if (null !== $constraint->min && bccomp($value, $min) < 0) {
            $this->context->buildViolation($constraint->minMessage)
                ->setParameter('{{ limit }}', $min)
                ->addViolation();
        }
    }
}
