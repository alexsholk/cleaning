<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\InvalidOptionsException;

/**
 * @Annotation
 * @Target({"CLASS", "ANNOTATION"})
 */
class AntiSpam extends Constraint
{
    public $message = 'Too frequent requests. Try again after {{ time }} minute.|Too frequent requests. Try again after {{ time }} minutes.';
    public $ipField;
    public $dateField;
    public $interval;

    public function __construct($options = null)
    {
        parent::__construct($options);

        if (!is_string($this->ipField) || !is_string($this->dateField)) {
            $message = sprintf(
                'Options "ipField" and "dateField" must be string %s',
                __CLASS__
            );

            throw new InvalidOptionsException($message, ['ipField', 'dateField']);
        }

        if (!is_integer($this->interval)) {
            $message = sprintf(
                'Option "interval" must be an integer %s',
                __CLASS__
            );

            throw new InvalidOptionsException($message, ['interval']);
        }
    }

    public function getRequiredOptions()
    {
        return ['ipField', 'dateField', 'interval'];
    }

    public function validatedBy()
    {
        return AntiSpamValidator::class;
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}