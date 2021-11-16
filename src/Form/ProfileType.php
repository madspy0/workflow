<?php

namespace App\Form;

use App\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('/dr_profile')
            ->add('firstname', null, ['label' => false, 'attr' => ['placeholder' => 'Ім\'я']])
            ->add('lastname', null, ['label' => false, 'attr' => ['placeholder' => 'Призвіще']])
            ->add('middlename', null, ['label' => false, 'attr' => ['placeholder' => 'По-батькові']])
            ->add('address', null, ['label' => 'Адреса'])
            ->add('localGoverment', null, ['label' => 'Назва органу влади'])
            ->add('link', null, ['label' => 'Посилання на сайт'])
            ->add('phone', null, ['label' => 'Телефон'])
            ->add('save', SubmitType::class, [
                'label' => 'Зберегти',
                'attr' => ['class' => 'save'],
            ])
        //    ->add('users')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class
        ]);
    }
}
