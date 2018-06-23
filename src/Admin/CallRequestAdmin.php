<?php

namespace App\Admin;


use App\Entity\CallRequest;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Sonata\CoreBundle\Form\Type\DateTimeRangePickerType;
use Sonata\DoctrineORMAdminBundle\Filter\DateTimeRangeFilter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CallRequestAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $ip = $this->getSubject()->getIp() ? $this->getSubject()->getIp()->getShortAddress() : '';
        $formMapper
            ->add('name')
            ->add('status', ChoiceType::class, ['choices' => array_flip(CallRequest::$statuses)])
            ->add('phone', PhoneNumberType::class)
            ->add('ip', null, ['disabled' => true, 'data' => $ip])
            ->add('createdAt', DateTimePickerType::class, ['disabled' => true]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $translatedChoiceItems = array_map(
            function ($item) {
                return $this->trans($item);
            },
            CallRequest::$statuses
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
            ->add(
                'phone',
                PhoneNumberType::class,
                [
                    'template' => 'sonata\CRUD\phone_list_field.html.twig'
                ]
            )
            ->add(
                'ip',
                null,
                [
                    'template' => 'sonata\CRUD\ip_list_field.html.twig'
                ]
                )
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
            ->add('name')
            ->add(
                'status',
                'doctrine_orm_string',
                [],
                ChoiceType::class,
                [
                    'choices' => array_flip(CallRequest::$statuses)
                ]
            )
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

    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->remove('create');
    }

}