<?php

namespace App\Form;

use App\Entity\DevelopmentSolution;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevelopmentSolutionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number',null,['label'=>'Номер'])
            ->add('solution',null,['label'=>'Рішення', 'attr'=>['rows'=>'15']])
            ->add('status',null,['label'=>'Результат', 'attr'=>['class'=>'form-switch']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DevelopmentSolution::class,
//            'novalidate' => 'novalidate',
        ]);
    }
}
