<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class ContainsGeom extends Constraint
{
    public $message = 'not a valid geom';

}