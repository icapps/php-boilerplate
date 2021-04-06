<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LocationMinimalProperties extends Constraint
{
    public $message = 'The location must have the minimal properties required ("lat", "lon")';
}
