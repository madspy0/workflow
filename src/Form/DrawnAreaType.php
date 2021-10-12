<?php

namespace App\Form;

use App\Entity\DrawnArea;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
USE Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DrawnAreaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('localGoverment')
            ->add('firstname')
            ->add('lastname')
            ->add('middlename')
  //          ->add('createdAt')
            ->add('address')
            ->add('use')
            ->add('numberSolution')
            ->add('solutedAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Дата',
                'html5' => false ])
//            ->add('publishedAt')
//            ->add('status')
//            ->add('geom')
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'save'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DrawnArea::class,
        ]);
    }
}
