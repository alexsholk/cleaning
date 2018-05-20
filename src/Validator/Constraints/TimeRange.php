<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\InvalidOptionsException;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class TimeRange extends Constraint
{
    public $minMessage = 'This value should be {{ limit }} or more.';
    public $maxMessage = 'This value should be {{ limit }} or less.';
    public $invalidMessage = 'This value should be a valid time string or DateTime object.';
    public $format = 'H:i';
    public $min;
    public $max;

    public function __construct($options = null)
    {
        parent::__construct($options);

        if (is_string($this->min)) {
            $this->min = \DateTime::createFromFormat($this->format, $this->min);
        }

        if (is_string($this->max)) {
            $this->max = \DateTime::createFromFormat($this->format, $this->max);
        }

        if (!$this->min instanceof \DateTimeInterface || !$this->max instanceof \DateTimeInterface) {
            $message = sprintf(
                'Options "min" and "max" must be valid string or DateTime object for constraint %s',
                __CLASS__
            );

            throw new InvalidOptionsException($message, ['min', 'max']);
        }
    }

    public function getRequiredOptions()
    {
        return ['min', 'max'];
    }

    public function validatedBy()
    {
        return TimeRangeValidator::class;
    }

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}