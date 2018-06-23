<?php

namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Sonata\CoreBundle\Form\Type\DateTimeRangePickerType;
use Sonata\DoctrineORMAdminBundle\Filter\DateTimeRangeFilter;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PaymentAdmin  extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('amount', NumberType::class)
            ->add('datetime', DateTimePickerType::class)
            ->add('order')
            ->add('cleaner')
            ->add('comment', TextareaType::class, ['attr' => ['cols' => '5', 'rows' => '10']]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('amount')
            ->add('order')
            ->add('cleaner')
            ->add('datetime', null, ['format' => 'd-m-Y H:i'])
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
            ->add('order')
            ->add('cleaner')
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