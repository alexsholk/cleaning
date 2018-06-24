<?php

namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\DateTimeRangePickerType;
use Sonata\DoctrineORMAdminBundle\Filter\DateTimeRangeFilter;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ServiceAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('code')
            ->add('price')
            ->add('available')
            ->add('countable')
            ->add('minCount')
            ->add('maxCount')
            ->add('step')
            ->add('weight')
            ->add('unit')
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'allow_delete' => true,
            ])
            ->add('shortCode');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('code')
            ->add('price')
            ->add('available')
            ->add('createdAt', null, ['format' => 'd-m-Y H:i'])
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
            ->add('code')
            ->add('available')
            ->add(
                'createdAt',
                DateTimeRangeFilter::class,
                [
                    'field_type' => DateTimeRangePickerType::class,
                    'field_options' => [
                        'field_options_start' => [
                            'format' => 'dd-MM-Y HH:mm',
                        ],
                        'field_options_end' => [
                            'format' => 'dd-MM-Y HH:mm',
                            'dp_use_current' => true,
                            'dp_show_today' => true,
                        ]
                    ]
                ]
            );
    }

}