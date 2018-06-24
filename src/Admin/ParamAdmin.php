<?php

namespace App\Admin;


use App\Entity\Param;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ParamAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('code', TextType::class, ['disabled' => true])
            ->add('value', TextareaType::class)
            ->add('category', ChoiceType::class, ['choices' => array_flip(Param::$groups)])
            ->add('type', ChoiceType::class, ['choices' => array_flip(Param::$types)]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('category', null, ['template' => 'sonata\CRUD\category_list_field.html.twig'])
            ->add('type', null, ['template' => 'sonata\CRUD\type_list_field.html.twig'])
            ->add(
                '_action',
                null,
                [
                    'actions' => [
                        'delete' => [],
                        'edit' => [],
                    ],
                ]
            );
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add(
                'category',
                'doctrine_orm_string',
                [],
                ChoiceType::class,
                [
                    'choices' => array_flip(Param::$groups),
                ]
            )
            ->add(
                'type',
                'doctrine_orm_string',
                [],
                ChoiceType::class,
                [
                    'choices' => array_flip(Param::$types),
                ]
            );
    }
}