<?php

namespace App\Form;

use App\Entity\ArchiveGround;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArchiveGroundType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('localGoverment')
            ->add('documentsType')
            ->add('docNumber')
            ->add('documentDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Дата документа',
                'input' => 'datetime_immutable',
                'input_format' => 'dd-MM-yyyy',
                'format' => 'dd-MM-yyyy',
                'html5' => false,
                'attr'=>['autocomplete'=>'off']])
            ->add('link')
       //     ->add('drawnArea')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArchiveGround::class,
            'csrf_protection' => false,
        ]);
    }
}
