<?php

namespace App\Form;

use App\Entity\ArchiveGroundGov;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArchiveGroundGovType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cadnum')
            ->add('registrationAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Дата документа',
                'input' => 'datetime_immutable',
                'input_format' => 'dd-MM-yyyy',
                'format' => 'dd-MM-yyyy',
                'html5' => false,
                'attr'=>['autocomplete'=>'off']])
       //     ->add('drawnArea')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArchiveGroundGov::class,
            'csrf_protection' => false,
        ]);
    }
}
