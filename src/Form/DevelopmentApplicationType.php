<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Region;
use App\Entity\City;
use App\Entity\DevelopmentApplication;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\DevelopmentSolutionFormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Validator\ContainsGeom;

class DevelopmentApplicationType extends AbstractType
{
    private $entityManager;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->entityManager = $options['entity_manager'];
        $builder
            ->add('applicantLastname', null, ['label' => 'Призвище'])
            ->add('applicantFirstname', null, ['label' => 'І\'мя'])
            ->add('applicantMiddlename', null, ['label' => 'По-батькові'])
            ->add('phoneNumber', null, ['label' => 'Номер телефона'])
            ->add('email',null,['label'=>'Поштова скринька'])
            ->add('applicantStreetAddress',null,['label'=>'Вулиця'])
            ->add('applicantBuild',null,['label'=>'Будинок'])
            ->add('country', EntityType::class, [
                'preferred_choices' => [$this->entityManager->getRepository(Country::class)->find(2)],
                'class' => Country::class,
                'choice_label' => 'title_ua',
                'placeholder' => 'Виберіть країну',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.id', 'ASC');
                },
                'attr'=>['class'=>'dcountries']
            ])
            ->add('postal')
            ->add('landAddress',null,['label'=>'Вулиця'])
            ->add('landCity')
            ->add('landRegion')
            ->add('landPostal')
            ->add('landCountry', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'title_ua',
                'placeholder' => 'Виберіть країну',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.id', 'ASC');
                },
                'attr'=>['class'=>'dcountries']
            ])
            ->add('landApplicantBuild',null, ['label'=>'Номер'])
            ->add('cadastreNumber', null, ['label'=>'Кадастровий номер'])
            ->add('area',null, ['label'=>'Площа'])
            ->add('purpose')
            ->add('use')
            ->add('planingDocumentation',null,['data'=>true,
                'label'=>'Необхідність розроблення містобудівної документації',
                'attr'=>['class'=>'form-switch']])
            ->add('typeDocumentation', ChoiceType::class, [
                'choices' => [
                    'First choice' => 'first choice',
                    'second choice' => 'second choice'
                ]])
            ->add('consent', CheckboxType::class, ['mapped' => false, 'label'=>'Згоден надати персональні данні'])
            ->add('geom',null,['label'=>false,'attr'=>['class'=>'hidden-geom']])
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DevelopmentApplication::class,
 //           'error_bubbling' => false,
//            'cascade_validation' => true
        ]);
        $resolver->setRequired('entity_manager');
    }

    protected function addElements(FormInterface $form, Country $country = null, Region $region = null)
    {
//        $form->add('country', EntityType::class, array(
//            'required' => true,
//            'data' => $country,
//            'placeholder' => 'Select a Country...',
//            'class' => Country::class
//        ));
// Neighborhoods empty, unless there is a selected City (Edit View)
        $regions = array();
        if ($country) {
            $repoRegions = $this->entityManager->getRepository(Region::class);
            $regions = $repoRegions->createQueryBuilder("r")
                ->where("r.country = :countryid")
                ->setParameter("countryid", $country->getId())
                ->getQuery()
                ->getResult();
        }
        $form->add('region', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Спочатку виберіть країну ...',
            'class' => Region::class,
            'choices' => $regions
        ));
        $cities = array();
        if($region) {
            $repoCities = $this->entityManager->getRepository(City::class);
            $cities = $repoCities->createQueryBuilder("c")
                ->where("c.region = :regionid")
                ->setParameter("regionid", $region->getId())
                ->getQuery()
                ->getResult();
        }
        $form->add('city', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Спочатку виберіть регіон ...',
            'class' => City::class,
            'choices' => $cities
        ));
    }

    function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        $country = empty($data['country']) ? null : $this->entityManager->getRepository(Country::class)->find($data['country']);
 //       $this->addElements($form, $country);
        $region = empty($data['region']) ? null : $this->entityManager->getRepository(Region::class)->find($data['region']);
        $this->addElements($form, $country, $region);
    }

    function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        $this->addElements($form, $data->getCountry());
    }
}
