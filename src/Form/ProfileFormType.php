<?php

namespace App\Form;

use App\Entity\DzkAdminObl;
use App\Entity\DzkAdminOtg;
use App\Entity\Profile;
use App\Entity\UsePlantCategory;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ProfileFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('/dr_profile')
            ->add('firstname', null, ['label' => false, 'attr' => ['placeholder' => 'Ім\'я']])
            ->add('lastname', null, ['label' => false, 'attr' => ['placeholder' => 'Прізвище']])
            ->add('middlename', null, ['label' => false, 'attr' => ['placeholder' => 'По-батькові']])
            ->add('address', null, ['label' => 'Адреса'])
            ->add('url', UrlType::class, ['label' => 'Посилання на сайт',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Будь ласка, введіть пароль',
                    ]),
                    new Url([
                        'message' => 'Будь ласка, введіть посилання на сайт',
                    ]),
                ],
            ])
            ->add('phone', null, ['label' => 'Телефон'])
            ->add('localGoverment', null, ['label' => 'Назва органу влади'])
            ->add('oblast', EntityType::class, [
                'label' => false,
                'class' => DzkAdminObl::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->select('partial o.{id, nameRgn}');
                },
                'choice_label' => 'nameRgn',
                'placeholder' => 'Область',
                'required' => true
            ]);
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
        ]);
    }
}
