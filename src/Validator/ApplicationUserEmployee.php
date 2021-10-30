<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ApplicationUserEmployee extends Constraint
{
    public $message = 'Only employee users are able to create applications.';
}
