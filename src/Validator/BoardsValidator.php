<?php

namespace App\Validator;
use App\Entity\DrawnArea;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Doctrine\ORM\EntityManagerInterface;

class BoardsValidator extends ConstraintValidator
{
    public $tokenStorage;
    public $em;

    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $em) {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint) {
        $user = $this->tokenStorage->getToken()->getUser();
        if (!$constraint instanceof ContainsGeom) {
            throw new UnexpectedTypeException($constraint, ContainsGeom::class);
        }
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }
        $repository = $this->em->getRepository(DrawnArea::class);
        if(!$repository->inBoard($value, $user->getProfile()->getOtg()->getGeom())) {
            //    dump($this->context->getPropertyPath('geom'));
            $this->context->buildViolation($constraint->message)
                //               ->atPath('geom')
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}