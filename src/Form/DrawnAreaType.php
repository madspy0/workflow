<?php

namespace App\Form;

use App\Entity\DrawnArea;
use App\Entity\UsePlantCategory;
use App\Entity\UsePlantSubCategory;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DrawnAreaType extends AbstractType
{

    private $entityManager;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->entityManager = $options['entity_manager'];
        $builder
            ->add('localGoverment', null, ['label' => 'Орган влади, який прийняв рішення'])
            ->add('firstname', null, ['label' => false, 'attr' => ['placeholder' => 'Ім\'я']])
            ->add('lastname', null, ['label' => false, 'attr' => ['placeholder' => 'Прізвище']])
            ->add('middlename', null, ['label' => false, 'attr' => ['placeholder' => 'По-батькові']])
            //          ->add('createdAt')
            ->add('documentsType', null, ['label' => 'Назва документа'])
            ->add('address', null, ['label' => 'Орієнтовне місце розташування (адреса)'])
//            ->add('use', ChoiceType::class, ['label'=>'Вид використання',
//                'choices' => [
//                    'First choice' => 'вибір',
//                    'second choice' => 'інший choice'
//                ]])
            ->add('useCategory', EntityType::class, [
                'label' => 'Майбутнє цільове призначення',
                'class' => UsePlantCategory::class,
                'choice_label' => 'title',
                'placeholder' => 'Оберіть категорію',
            ])
            ->add('numberSolution', null, ['label' => 'Номер документа'])
            ->add('solutedAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Дата документа',
                'input' => 'datetime_immutable',
                'input_format' => 'dd-MM-yyyy',
                'format' => 'dd-MM-yyyy',
                'html5' => false,
                'attr'=>['autocomplete'=>'off']])
//            ->add('publishedAt')
            ->add('area', null, ['label' => 'Площа', 'attr' => ['readonly' => 'true']])
            ->add('link', null, ['label' => 'Посилання на сайт'])
//            ->add('status', ChoiceType::class, ['label' => 'Статус',
//                'choices' => [
//                    'Внесено' => 'draft',
//                    'Підтверджено' => 'numbered',
//                    'Опубліковано' => 'published',
//                    'Скасувано' => 'rejected'
//                ]])
            ->add('geom', HiddenType::class)
//            ->add('save', SubmitType::class, [
//                'label' => 'Зберегти',
//                'attr' => ['class' => 'save'],
//            ])
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DrawnArea::class,
            'entity_manager' => null,
            'csrf_protection' => false
        ]);
    }

    function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        $category = empty($data['useCategory']) ? null : $this->entityManager->getRepository(UsePlantCategory::class)->find($data['useCategory']);
        $this->addElements($form, $category);
    }

    function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        $data->setArea($this->formatArea($data->getArea()));
        $this->addElements($form, $data->getUseCategory());
    }

    protected function addElements(FormInterface $form, UsePlantCategory $category = null)
    {
        $subcategories = null === $category ? [] : $category->getUsePlantSubCategories();

        $form->add('useSubCategory', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Спочатку виберіть цільове призначення',
            'class' => UsePlantSubCategory::class,
            'choices' => $subcategories,
            'choice_label' => 'title',
            'label' => '_'
        ));
    }

    protected function formatArea($area)
    {
        $pf = mb_convert_encoding('&#x00B2;', 'UTF-8', 'HTML-ENTITIES');
//        if ($area > 10000) {
            $output = round(($area / 10000) * 100) / 100 . ' Га';
//        } else {
//            $output = round($area * 100) / 100 . ' м ' . $pf;
//        }
        return $output;
    }
}
