<?php

namespace App\Admin;


use App\Entity\Order;
use App\Model\AreaModel;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Sonata\CoreBundle\Form\Type\DateTimeRangePickerType;
use Sonata\DoctrineORMAdminBundle\Filter\DateTimeRangeFilter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OrderAdmin extends AbstractAdmin
{
    public function getFilterParameters()
    {
        $this->datagridValues = array_merge(
            [
                'status' => [
                    'value' => 0
                ],
            ],
            $this->datagridValues
        );

        return parent::getFilterParameters();
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('Order')
                ->add('name', TextType::class)
                ->add(
                    'phone',
                    PhoneNumberType::class
                )
                ->add(
                    'status',
                    ChoiceType::class,
                    [
                        'choices' => array_flip(Order::$statuses)
                    ]
                )
                ->add('datetime', DateTimePickerType::class)
                ->add('comment', TextareaType::class, ['required' => false])
                ->end()
                ->with('Service')
                    ->add(
                        'items',
                        CollectionType::class,
                        [
                            'by_reference' => false
                        ],
                        [
                            'edit' => 'inline',
                            'inline' => 'table',
                        ]
                    )
                    ->add(
                        'frequency',
                        ChoiceType::class,
                        [
                            'choices' => array_flip(Order::$frequencies)
                        ]
                    )
                    ->add(
                        'cleaners',
                        null,
                        [
                            'by_reference' => false
                        ]
                    )
                ->end()
                ->with('Cost')
                    ->add('baseCost', NumberType::class)
                    ->add('servicesCost', NumberType::class, ['required' => false])
                    ->add('additionalCost', NumberType::class, ['required' => false])
                    ->add('additionalCostComment', TextareaType::class, ['required' => false])
                    ->add('discountFrequency', NumberType::class, ['required' => false])
                    ->add('promocode')
                    ->add('discountPromocode', NumberType::class, ['required' => false])
                    ->add('discount', NumberType::class, ['required' => false])
                    ->add('paid', NumberType::class)
                ->end()
                ->with('Address')
                    ->add('city')
                    ->add(
                        'area',
                        ChoiceType::class,
                        [
                            'choices' => array_flip(AreaModel::getAll()),
                            'required' => false,
                        ]
                    )
                    ->add('street')
                    ->add('home')
                    ->add('building')
                    ->add('flat')
                ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'status',
                'doctrine_orm_string',
                [],
                ChoiceType::class,
                [
                    'choices' => array_flip(Order::$statuses)
                ]
            )
            ->add('name')
            ->add('city')
            ->add('promocode')
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

    protected function configureListFields(ListMapper $listMapper)
    {
        $translatedChoiceItems = array_map(
            function ($item) {
                return $this->trans($item);
            },
            Order::$statuses
        );

        $listMapper
            ->addIdentifier('name')
            ->add(
                'status',
                'choice',
                [
                    'editable' => true,
                    'choices' => $translatedChoiceItems,
                ]
            )
            ->add('address')
            ->add(
                'phone',
                PhoneNumberType::class,
                [
                    'template' => 'sonata\CRUD\phone_list_field.html.twig'
                ]
            )
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
}
