<?php

namespace App\Validator;

use App\Entity\DrawnArea;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Doctrine\ORM\EntityManagerInterface;

class ContainsGeomValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ContainsGeom) {
            throw new UnexpectedTypeException($constraint, ContainsGeom::class);
        }

 //       $value = $object->getGeom();
        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }

        // access your configuration options like this:
//        if ('strict' === $constraint->mode) {
//            // ...
//        }

        $repository = $this->em->getRepository(DrawnArea::class);
        if(!$repository->isValid($value)) {
        //    dump($this->context->getPropertyPath('geom'));
            $this->context->buildViolation($constraint->message)
 //               ->atPath('geom')
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
