<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Sonata\CoreBundle\Form\Type\DateTimeRangePickerType;
use Sonata\DoctrineORMAdminBundle\Filter\DateTimeRangeFilter;

class PromocodeAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('code')
            ->add('discount')
            ->add('quantity')
            ->add('startDate', DateTimePickerType::class)
            ->add('endDate', DateTimePickerType::class)
            ->add('enabled')
            ->add(
                'orders',
            null,
                [
                    'by_reference' => false
                ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('code')
            ->add('discount')
            ->add('startDate', null, ['format' => 'd-m-Y H:i'])
            ->add('endDate', null, ['format' => 'd-m-Y H:i'])
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
            ->add('code')
            ->add('discount')
            ->add(
                'startDate',
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
            )
            ->add(
                'endDate',
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