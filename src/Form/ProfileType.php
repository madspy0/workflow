<?php

namespace App\Form;

use App\Entity\DzkAdminOtg;
use App\Entity\Profile;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('/dr_profile')
            ->add('firstname', null, ['label' => false, 'attr' => ['placeholder' => 'Ім\'я']])
            ->add('lastname', null, ['label' => false, 'attr' => ['placeholder' => 'Прізвище']])
            ->add('middlename', null, ['label' => false, 'attr' => ['placeholder' => 'По-батькові']])
            ->add('address', null, ['label' => 'Адреса'])
            ->add('localGoverment', null, ['label' => 'Назва органу влади'])
            ->add('url', null, ['label' => 'Посилання на сайт'])
            ->add('phone', null, ['label' => 'Телефон'])
            ->add('otg', EntityType::class, [
                'class' => DzkAdminOtg::class,
                'choice_label' => 'name_rgn',
                'label' => 'ОТГ'
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('ecpFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
//                'delete_label' => '...',
//                'download_uri' => '...',
//                'download_label' => '...',
                'asset_helper' => true,
            ]);
//            ->add('save', SubmitType::class, [
//                'label' => 'Зберегти',
//                'attr' => ['class' => 'save'],
//            ])
        //    ->add('users')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
            'csrf_protection' => false,
        ]);
    }
}
