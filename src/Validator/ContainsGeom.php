<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
/**
 * @Annotation
 */
class ContainsGeom extends Constraint
{
    public $message = 'not a valid geom';

}
