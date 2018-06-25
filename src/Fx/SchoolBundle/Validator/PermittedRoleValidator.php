<?php

namespace Fx\SchoolBundle\Validator;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PermittedRoleValidator extends ConstraintValidator
{
    private $authorizationChecker;

    private $promotorPermittedRoles    = ['visitante'];



    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }


    public function validate($value, Constraint $constraint)
    {
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) return;

        if ($this->authorizationChecker->isGranted('ROLE_VISITANTE') &&
            in_array($value, $this->promotorPermittedRoles)
        ) return;

        $this->context->buildViolation($constraint->message)
            ->setParameter('%rol%', $value)
            ->addViolation();
    }
}
