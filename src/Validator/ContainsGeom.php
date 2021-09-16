<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class ContainsGeom extends Constraint
{
    public $message = 'not a valid geom';

//    public function getTargets()
//    {
//        return self::CLASS_CONSTRAINT;
//    }
}
