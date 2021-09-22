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
        $ukraineCountry = $this->entityManager->getRepository(Country::class)->findBy(['title_ru'=>'Украина']);
        $builder
            ->add('applicantLastname', null, ['label' => 'Призвище'])
            ->add('applicantFirstname', null, ['label' => 'І\'мя'])
            ->add('applicantMiddlename', null, ['label' => 'По-батькові'])
            ->add('phoneNumber', null, ['label' => 'Номер телефона'])
            ->add('email',null,['label'=>'Поштова скринька'])
            ->add('applicantStreetAddress',null,['label'=>'Вулиця'])
            ->add('applicantBuild',null,['label'=>'Будинок'])
            ->add('country', EntityType::class, [
                'preferred_choices' => $ukraineCountry,
                'class' => Country::class,
                'choice_label' => 'title_ua',
                'placeholder' => 'Виберіть країну',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.title_ru', 'ASC');
                },
                'attr'=>['class'=>'dcountries'],
                'label'=>'Країни'
            ])
            ->add('postal',null, ['label'=>'Поштовий індекс'])
            ->add('landAddress',null,['label'=>'Вулиця'])
            ->add('landPostal',null, ['label'=>'Поштовий індекс'])
            ->add('landCountry', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'title_ua',
                'placeholder' => 'Виберіть країну',
                'choices'=>$ukraineCountry,
                'attr'=>['class'=>'dcountries'],
                'label'=>'Країни'
            ])
            ->add('landApplicantBuild',null, ['label'=>'Номер'])
            ->add('cadastreNumber', null, ['label'=>'Кадастровий номер'])
            ->add('area',null, ['label'=>'Площа (га)'])
            ->add('purpose', null, ['label'=>'Цільове призначення'])
            ->add('use', null, ['label'=>'Вид використання'])
            ->add('planingDocumentation',null,['data'=>true,
                'label'=>'Необхідність розроблення містобудівної документації',
                'attr'=>['class'=>'form-switch']])
            ->add('typeDocumentation', ChoiceType::class, [
                'choices' => [
                    'First choice' => 'first choice',
                    'second choice' => 'second choice'
                ], 'label'=> 'Тип документації'])
            ->add('consent', CheckboxType::class, ['mapped' => false, 'label'=>'Згоден надати персональні данні'])
            ->add('geom',null,['label'=>false,'attr'=>['class'=>'hidden-geom'],'required'=>false])
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

    protected function addElements(FormInterface $form, Country $country = null, Region $region = null, Country $landCoutry = null, Region $landRegion = null)
    {
//        $form->add('country', EntityType::class, array(
//            'required' => true,
//            'data' => $country,
//            'placeholder' => 'Select a Country...',
//            'class' => Country::class
//        ));
// Neighborhoods empty, unless there is a selected City (Edit View)
        $this->addRegions($country, $landCoutry, $form);

        $this->addCities($region, $landRegion, $form);
    }

    function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        $country = empty($data['country']) ? null : $this->entityManager->getRepository(Country::class)->find($data['country']);
 //       $this->addElements($form, $country);
        $region = empty($data['region']) ? null : $this->entityManager->getRepository(Region::class)->find($data['region']);
        $landCountry = empty($data['landCountry']) ? null : $this->entityManager->getRepository(Country::class)->find($data['landCountry']);
        $landRegion = empty($data['landRegion']) ? null : $this->entityManager->getRepository(Region::class)->find($data['landRegion']);
        $this->addElements($form, $country, $region, $landCountry, $landRegion);
    }

    function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        $this->addElements($form, $data->getCountry(), $data->getRegion(), $data->getLandCountry(), $data->getLandRegion());
    }

    function addRegions($country, $landCountry, $form) {
        $regions = [];
        $landRegions = [];
        if ($country) {
            $regions = $this->searchRegions($country);
        }
        if($landCountry) {
            $landRegions = $this->searchRegions($landCountry);
        }
        $form->add('region', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Спочатку виберіть країну ...',
            'class' => Region::class,
            'choices' => $regions,
            'attr'=>['class'=>'dregions'],
            'label'=>'Регіони'
        ));
        $form->add('landRegion', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Спочатку виберіть країну ...',
            'class' => Region::class,
            'choices' => $landRegions,
            'attr'=>['class'=>'dregions'],
            'label'=>'Регіони'
        ));
    }

    function addCities($region, $landRegion, $form) {
        $cities = [];
        $landCities = [];
        if($region) {
            $cities = $this->searchCities($region);
        }
        if($landRegion) {
            $landCities = $this->searchCities($landRegion);
        }
        $form->add('city', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Спочатку виберіть регіон ...',
            'class' => City::class,
            'choices' => $cities,
            'label'=>'Міста'
        ));
        $form->add('landCity', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Спочатку виберіть регіон ...',
            'class' => City::class,
            'choices' => $landCities,
            'label'=>'Міста'
        ));
    }
    function searchRegions($country)
    {
        $repoRegions = $this->entityManager->getRepository(Region::class);
        return $repoRegions->createQueryBuilder("r")
            ->where("r.country = :countryid")
            ->setParameter("countryid", $country->getId())
            ->getQuery()
            ->getResult();
    }
    function searchCities($region)
    {
        return $this->entityManager->getRepository(City::class)
            ->createQueryBuilder("c")
            ->where("c.region = :regionid")
            ->setParameter("regionid", $region->getId())
            ->getQuery()
            ->getResult();
    }
}
