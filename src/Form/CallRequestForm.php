<?php

namespace App\Form;

use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CallRequestForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['Default'],
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required'           => true,
                'translation_domain' => false,
            ])
            ->add('phone', PhoneNumberType::class, [
                'required'           => true,
                'translation_domain' => false,
            ])
            ->add('submit', SubmitType::class, [
                'translation_domain' => false,
            ]);
    }

    public function getBlockPrefix()
    {
        return 'call_request';
    }
}