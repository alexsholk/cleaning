<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewForm extends AbstractType
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
            ->add('text', TextareaType::class, [
                'required'           => true,
                'translation_domain' => false,
            ])
            ->add('submit', SubmitType::class, [
                'translation_domain' => false,
            ]);
    }

    public function getBlockPrefix()
    {
        return 'review';
    }
}