<?php

namespace App\Form;

use App\Tools\ChoicesHelper;
use App\Entity\Service;
use App\Validator\Constraints\TimeRange;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

/**
 * Форма заказа - шаг 1
 * (базовые услуги и дата уборки)
 */
class OrderForm1 extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        // Разрешить дополнительные поля
        $resolver->setDefault('allow_extra_fields', true);

        $resolver->setRequired([
            'services',             // Услуги
            'min_time_to_cleaning', // Минимальное время до уборки (минут)
            'max_time_to_cleaning', // Максимальное время до уборки (дней)
        ]);

        $resolver->setDefaults([
            'min_time'  => '9:00',  // Минимальное время заказа
            'max_time'  => '18:00', // Максимальное время заказа
            'time_step' => 60,      // Интервал
        ]);

        $resolver->setAllowedTypes('services', 'array');
        $resolver->setAllowedTypes('min_time_to_cleaning', 'int');
        $resolver->setAllowedTypes('max_time_to_cleaning', 'int');
        $resolver->setAllowedTypes('min_time', 'string');
        $resolver->setAllowedTypes('max_time', 'string');
        $resolver->setAllowedTypes('time_step', 'int');
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $services = $options['services'];

        foreach ($services as $service) {
            if (!$service instanceof Service) {
                throw new InvalidOptionsException('Option "services" must contain only ModelBundle\Entity\Service objects.');
            }

            if (!$service->getCountable()) {
                throw new InvalidOptionsException('Services must be countable.');
            }

            $choices = ChoicesHelper::generateChoices(
                $service->getMinCount(), $service->getMaxCount(),
                $service->getStep(), $service->getUnitForms()
            );

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

        $startDatetime = new \DateTime();
        $startDatetime->add(new \DateInterval('PT'.$options['min_time_to_cleaning'].'M'));
        $startDatetime->setTime(1 + (int)$startDatetime->format('G'), 0);

        $endDatetime = new \DateTime();
        $endDatetime->add(new \DateInterval('P'.$options['max_time_to_cleaning'].'D'));
        $endDatetime->setTime(23, 59, 59);

        $timeList = ChoicesHelper::generateTimeList($options['min_time'], $options['max_time'], $options['time_step']);

        $builder
            ->add('datetime', DateTimeType::class, [
                'widget'             => 'single_text',
                'input'              => 'datetime',
                'format'             => 'dd.MM.yyyy HH:mm',
                'translation_domain' => false,
                'constraints'        => [
                    new Constraints\NotBlank(),
                    new Constraints\DateTime(['format' => 'd.m.Y H:i']),
                    new Constraints\Range(['min' => $startDatetime, 'max' => $endDatetime]),
                    new TimeRange(['min' => $options['min_time'], 'max' => $options['max_time']]),
                ],
                'attr'               => [
                    'data-min-date'    => $startDatetime->format('d.m.Y'),
                    'data-max-date'    => $endDatetime->format('d.m.Y'),
                    'data-min-time'    => $options['min_time'],
                    'data-max-time'    => $options['max_time'],
                    'data-allow-times' => \json_encode($timeList),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'translation_domain' => false,
            ]);

        /**
         * Преобразование объекта DateTime в строку
         * в случае валидации значений из сессии
         */
        $builder
            ->get('datetime')
            ->addViewTransformer(new CallbackTransformer(function ($datetime) {
                return $datetime;
            }, function ($datetime) {
                if ($datetime instanceof \DateTimeInterface) {
                    return $datetime->format('d.m.Y H:i');
                }
                return $datetime;
            }));
    }

    public function getBlockPrefix()
    {
        return 'order';
    }
}