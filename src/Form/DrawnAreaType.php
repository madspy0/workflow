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
            ->add('localGoverment')
            ->add('firstname')
            ->add('lastname')
            ->add('middlename')
            //          ->add('createdAt')
            ->add('address')
//            ->add('use', ChoiceType::class, ['label'=>'Вид використання',
//                'choices' => [
//                    'First choice' => 'вибір',
//                    'second choice' => 'інший choice'
//                ]])
            ->add('useCategory', EntityType::class, [
                'label' => 'Вид використання',
                'class' => UsePlantCategory::class,
                'choice_label' => 'title',
                'placeholder' => 'Оберіть категорію',
            ])
            ->add('numberSolution')
            ->add('solutedAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Дата',
                'input' => 'datetime_immutable',
                'html5' => false])
//            ->add('publishedAt')
            ->add('area',null, ['label'=>'Площа', 'attr'=>['readonly' => 'true']])
            ->add('status', ChoiceType::class, ['label' => 'Статус',
                'choices' => [
                    'Внесено' => 'draft',
                    'Підтверджено' => 'numbered',
                    'Опубліковано' => 'published',
                    'Скасувано' => 'rejected'
                ]])
            ->add('geom', HiddenType::class)
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'save'],
            ]);
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DrawnArea::class,
            'entity_manager' => null,
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
        $this->addElements($form, $data->getUseCategory());
    }

    protected function addElements(FormInterface $form, UsePlantCategory $category = null)
    {
        $subcategories = null === $category ? [] : $category->getUsePlantSubCategories();

        $form->add('useSubCategory', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Спочатку виберіть категорию ...',
            'class' => UsePlantSubCategory::class,
            'choices' => $subcategories,
            'choice_label' => 'title',
            'label' => 'Субкатегории'
        ));
    }
}
