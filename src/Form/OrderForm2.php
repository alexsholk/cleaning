<?php

namespace App\Form;

use App\Tools\ChoicesHelper;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use App\Entity\Order;
use App\Entity\Service;
use App\Validator\Constraints\Promocode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Форма заказа - шаг 2
 * (дополнительные услуги, периодичность, контакты, промокод)
 */
class OrderForm2 extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'constraints' => [
                new Constraints\Callback([
                    'callback' => [$this, 'checkPromocode'],
                ])
            ],
        ]);

        $resolver->setRequired([
            'services', // Услуги
        ]);

        $resolver->setAllowedTypes('services', 'array');
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $services = $options['services'];

        foreach ($services as $service) {
            if (!$service instanceof Service) {
                throw new InvalidOptionsException('Option "services" must contain only ModelBundle\Entity\Service objects.');
            }

            $min     = $service->getMinCount() ?? 1;
            $max     = $service->getMaxCount() ?? 1;
            $step    = $service->getStep() ?? 1;
            $choices = ChoicesHelper::generateChoices($min, $max, $step, $service->getUnitForms());
            // Добавление нулевого значения, т.к. услуги не обязательные
            $choices = array_merge(['' => 0], $choices);

            $builder
                ->add('service_'.$service->getId(), ChoiceType::class, [
                    'choices'                   => $choices,
                    'choice_translation_domain' => false,
                    'translation_domain'        => false,
                    'constraints'               => [
                        new Constraints\NotBlank(),
                        new Constraints\Choice(['choices' => array_values($choices)]),
                    ],
                ]);
        }

        // Периодичность уборки
        $choices = array_flip(Order::$frequencies);

        $builder
            ->add('frequency', ChoiceType::class, [
                'choices'                   => $choices,
                'expanded'                  => true,
                'choice_translation_domain' => 'order',
                'translation_domain'        => false,
                'data'                      => $options['data']['frequency'] ?? Order::FREQUENCY_ONCE,
                'constraints'               => [
                    new Constraints\NotBlank(),
                    new Constraints\Choice(['choices' => array_values($choices)]),
                ],

            ]);

        // Промокод и кнопка проверки
        $builder
            ->add('promocode', TextType::class, [
                'required'           => false,
                'translation_domain' => false,
                'constraints'        => [
                    new Promocode(),
                ]
            ])
            ->add('check', ButtonType::class, [
                'translation_domain' => false,
            ])
            ->add('clear', ButtonType::class, [
                'translation_domain' => false,
            ]);

        // Контактная информация
        $builder
            ->add('name', TextType::class, [
                'required'           => true,
                'translation_domain' => false,
                'constraints'        => [
                    new Constraints\NotBlank(),
                ]
            ])
            ->add('phone', PhoneNumberType::class, [
                'required'           => true,
                'translation_domain' => false,
                'constraints'        => [
                    new Constraints\NotBlank(),
                    new AssertPhoneNumber(),
                ]
            ]);

        // Адрес
        $builder
            ->add('city', TextType::class, [
                'required'           => true,
                'translation_domain' => false,
                'data'               => $options['data']['city'] ?? Order::DEFAULT_CITY,
                'constraints'        => [
                    new Constraints\NotBlank(),
                ]
            ])
            ->add('street', TextType::class, [
                'required'           => true,
                'translation_domain' => false,
                'constraints'        => [
                    new Constraints\NotBlank(),
                ]
            ])
            ->add('home', TextType::class, [
                'required'           => true,
                'translation_domain' => false,
                'constraints'        => [
                    new Constraints\NotBlank(),
                ]
            ])
            ->add('building', TextType::class, [
                'required'           => false,
                'translation_domain' => false,
            ])
            ->add('flat', TextType::class, [
                'required'           => true,
                'translation_domain' => false,
                'constraints'        => [
                    new Constraints\NotBlank(),
                ]
            ]);

        // Комментарий к заказу
        $builder
            ->add('comment', TextareaType::class, [
                'required'           => false,
                'translation_domain' => false,
            ]);

        // Кнопка отправки формы
        $builder
            ->add('submit', SubmitType::class, [
                'translation_domain' => false,
            ]);
    }

    public function getBlockPrefix()
    {
        return 'order';
    }

    public function checkPromocode($data, ExecutionContextInterface $context)
    {
        // todo проверять адрес и промокод
    }
}