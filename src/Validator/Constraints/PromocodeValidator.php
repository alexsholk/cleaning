<?php

namespace App\Validator\Constraints;

use App\Model\PromocodeModel;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PromocodeValidator extends ConstraintValidator
{
    /** @var PromocodeModel */
    private $promocodeModel;

    public function __construct(PromocodeModel $promocodeModel)
    {
        $this->promocodeModel = $promocodeModel;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Promocode) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Promocode');
        }

        if (null === $value) {
            return;
        }

        // Поиск промокода
        $repository = $this->promocodeModel->getRepository(\App\Entity\Promocode::class);
        /** @var \App\Entity\Promocode $promocode */
        $promocode = $repository->getEnabledPromocode($value);

        // Текущее время и дата
        $now = new \DateTime();

        if (is_null($promocode)) {
            // Если промокод не найден
            $this->context
                ->buildViolation($constraint->notFoundMessage)
                ->addViolation();
        } elseif (!is_null($promocode->getStartDate()) && $promocode->getStartDate() > $now) {
            // Если промокод ещё не начал действовать
            $this->context
                ->buildViolation($constraint->notStartedMessage)
                ->setParameter('{{ startDate }}', $promocode->getStartDate()->format('d.m.Y'))
                ->addViolation();
        } elseif (!is_null($promocode->getEndDate()) && $promocode->getEndDate() < $now) {
            // Если срок действия промокода истек
            $this->context
                ->buildViolation($constraint->expiredMessage)
                ->addViolation();
        } elseif ($this->promocodeModel->getAvailableCount($value) <= 0) {
            // Если все промокоды использованы
            $this->context
                ->buildViolation($constraint->endedMessage)
                ->addViolation();
        }
    }
}