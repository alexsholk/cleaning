<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class Promocode extends Constraint
{
    public $notFoundMessage   = 'Promocode not found.';
    public $expiredMessage    = 'Expired promocode.';
    public $endedMessage      = 'Promocode ended.';
    public $notStartedMessage = 'Promocode will start working on {{ startDate }}.';

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}