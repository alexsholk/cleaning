<?php

namespace App\Admin;

use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;

class CleanerAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('phone', PhoneNumberType::class)
            ->add('additionalPhone', PhoneNumberType::class)
            ->add(
                'orders',
                null,
                [
                    'by_reference' => false
                ]
            );
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add(
                'phone',
                PhoneNumberType::class,
                [
                    'template' => 'sonata\CRUD\phone_list_field.html.twig'
                ]
            )
            ->add(
                'additionalPhone',
                PhoneNumberType::class,
                [
                    'template' => 'sonata\CRUD\phone_list_field.html.twig'
                ]
            )
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
            ->add('name');
    }
}