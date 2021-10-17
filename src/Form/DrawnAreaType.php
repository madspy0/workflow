<?php

namespace App\Form;

use App\Entity\DrawnArea;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
            ->add('use', ChoiceType::class, ['label'=>'Вид використання',
                'choices' => [
                    'First choice' => 'вибір',
                    'second choice' => 'інший choice'
                ]])
            ->add('numberSolution')
            ->add('solutedAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Дата',
                'input' => 'datetime_immutable',
                'html5' => false ])
//            ->add('publishedAt')
            ->add('status')
            ->add('status', ChoiceType::class, ['label'=>'Статус',
                'choices' => [
                    'Внесено' => 'draft' ,
                    'Підтверджено' => 'numbered',
                    'Опубліковано' => 'published',
                    'Скасувано' => 'rejected'
                ]])
            ->add('geom', HiddenType::class)
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'save'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DrawnArea::class
        ]);
    }
}
