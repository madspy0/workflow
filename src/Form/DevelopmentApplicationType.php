<?php

namespace App\Form;

use App\Entity\DevelopmentApplication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\DevelopmentSolutionFormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class DevelopmentApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('applicantLastname', null,['label'=>'Заявник', 'attr' => ['placeholder' => 'Призвище']] )
            ->add('applicantFirstname', null,['attr' => ['placeholder' => 'І\'мя']])
            ->add('applicantMiddlename', null,['attr' => ['placeholder' => 'По-батькові']])
            ->add('phoneNumber', null,['label'=>'Номер телефона'])
            ->add('email')
            ->add('applicantStreetAddress')
            ->add('city')
            ->add('region')
            ->add('country')
            ->add('postal')
            ->add('landAddress')
            ->add('landCity')
            ->add('landRegion')
            ->add('landPostal')
            ->add('landCountry')
            ->add('cadastreNumber')
            ->add('area')
            ->add('purpose')
            ->add('use')
            ->add('planingDocumentation')
            ->add('typeDocumentation')
            ->add('consent', CheckboxType::class, ['mapped' => false])
            ->add('geom', HiddenType::class)
//            ->add('status')
//            ->add('createdAt')
//            ->add('solution', DevelopmentSolutionFormType::class)
//            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DevelopmentApplication::class,
        ]);
    }
}
