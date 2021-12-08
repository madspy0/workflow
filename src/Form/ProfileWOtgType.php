<?php

namespace App\Form;

use App\Entity\DzkAdminOtg;
use App\Entity\Profile;
use App\Entity\UsePlantCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ProfileWOtgType extends AbstractType
{
    private $entityManager;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->entityManager = $options['entity_manager'];
        $builder
            ->setAction('/dr_profile')
            ->add('firstname', null, ['label' => false, 'attr' => ['placeholder' => 'Ім\'я']])
            ->add('lastname', null, ['label' => false, 'attr' => ['placeholder' => 'Прізвище']])
            ->add('middlename', null, ['label' => false, 'attr' => ['placeholder' => 'По-батькові']])
            ->add('address', null, ['label' => 'Адреса'])
            ->add('url', null, ['label' => 'Посилання на сайт'])
            ->add('phone', null, ['label' => 'Телефон'])
            ->add('localGoverment', null,['label' => 'Назва органу влади'])
 //           ->add('otg', HiddenType::class
//                EntityType::class,
//                [
//                'class' => DzkAdminOtg::class,
//                'choice_label' => 'name_rgn',
//                'label' => false,
//                'placeholder' => 'Назва органу влади',
//                'attr'=>['class'=>'form-control']
//                // 'multiple' => true,
//                // 'expanded' => true,
//            ]
//            )

//            ->add('ecpFile', VichFileType::class, [
//                'required' => false,
//                'allow_delete' => true,
////                'delete_label' => '...',
////                'download_uri' => '...',
////                'download_label' => '...',
//                'asset_helper' => true,
//            ]);
//            ->add('save', SubmitType::class, [
//                'label' => 'Зберегти',
//                'attr' => ['class' => 'save'],
//            ])
        //    ->add('users')
        ;
  //      $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

//    function onPreSubmit(FormEvent $event)
//    {
//        $form = $event->getForm();
//        $data = $event->getData();
//        $data['otg'] = $this->entityManager->getRepository(DzkAdminOtg::class)->find($data['otg']);
//        $event->setData($data);
//    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
            'csrf_protection' => false,
            'entity_manager' => null,
        ]);
    }
}
