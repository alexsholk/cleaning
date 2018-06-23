<?php

namespace App\Admin;


use App\Entity\Order;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OrderAdmin extends AbstractAdmin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('Order')
                ->add('name', TextType::class)
                ->add('status', ChoiceType::class, ['choices' => array_flip(Order::$statuses)])
                ->add(
                    'phone',
                    PhoneNumberType::class
                )
                ->add('datetime', DateTimePickerType::class)
                ->add('promocode',null )
                ->add('items', null, ['by_reference' => false])
                ->add('comment', TextareaType::class, ['required' => false])
                ->end()
                ->with('Address')
                    ->add('city')
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
            ->add('name');
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
            ->add('city')
            ->add(
                'phone',
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
}
