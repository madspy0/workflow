<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class Boards extends Constraint
{
    public $message = 'не в межах ОМС';

//    public function getTargets()
//    {
//        return self::CLASS_CONSTRAINT;
//    }
}
