<?php

namespace App\Admin;


use App\Entity\InventoryMovement;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Sonata\CoreBundle\Form\Type\DateTimeRangePickerType;
use Sonata\DoctrineORMAdminBundle\Filter\DateTimeRangeFilter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class InventoryMovementAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add(
                'type',
                ChoiceType::class,
                [
                    'choices' => array_flip(InventoryMovement::$types)
                ]
            )
            ->add('inventory')
            ->add('cleaner')
            ->add('quantity', NumberType::class)
            ->add('datetime', DateTimePickerType::class);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $translatedTypeItems = array_map(
            function ($item) {
                return $this->trans($item);
            },
            InventoryMovement::$types
        );
        $listMapper
            ->addIdentifier('inventory')
            ->add(
                'type',
                'choice',
                [
                    'editable' => true,
                    'choices' => $translatedTypeItems,
                ]
            )
            ->add('cleaner')
            ->add('quantity', NumberType::class)
            ->add('datetime', null,  ['format' => 'd-m-Y H:i'])
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
            ->add(
                'type',
                'doctrine_orm_string',
                [],
                ChoiceType::class,
                [
                    'choices' => array_flip(InventoryMovement::$types)
                ]
            )
            ->add('inventory')
            ->add('cleaner')
            ->add(
                'datetime',
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