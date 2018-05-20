<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TimeRangeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof TimeRange) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\TimeRange');
        }

        if (null === $value) {
            return;
        }

        if (is_string($value)) {
            $value = \DateTime::createFromFormat($constraint->format, $value);
        }

        if (!$value instanceof \DateTimeInterface) {
            $this->context
                ->buildViolation($constraint->invalidMessage)
                ->addViolation();

            return;
        }

        /** @var \DateTimeInterface $min, $max */
        $min = $constraint->min;
        $max = $constraint->max;

        if ($value->format('H:i:s') < $min->format('H:i:s')) {
            $this->context
                ->buildViolation($constraint->minMessage)
                ->setParameter('{{ value }}', $value->format($constraint->format))
                ->setParameter('{{ limit }}', $min->format($constraint->format))
                ->addViolation();
        }

        if ($value->format('H:i:s') > $max->format('H:i:s')) {
            $this->context
                ->buildViolation($constraint->maxMessage)
                ->setParameter('{{ value }}', $value->format($constraint->format))
                ->setParameter('{{ limit }}', $max->format($constraint->format))
                ->addViolation();
        }
    }
}