<?php

namespace App\Validator;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ApplicationUserEmployeeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ApplicationUserEmployee) {
            throw new UnexpectedTypeException($constraint, ApplicationUserEmployee::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        /** @var User $value */
        $missingEmployeeRole = $value->getRoles()->filter(function (Role $role) {
            return $role->getRole() === Role::ROLE_EMPLOYEE;
        })->isEmpty();

        if ($missingEmployeeRole) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}