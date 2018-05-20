<?php

namespace App\Validator\Constraints;

use App\Entity\Param;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ParamValueValidator extends ConstraintValidator
{
    protected $validator;
    protected $field = 'value';

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($param, Constraint $constraint)
    {
        /** @var Param $param */
        switch ($param->getType()) {
            case Param::TYPE_STRING:
            case Param::TYPE_TEXT:
            case Param::TYPE_HTML:
            case Param::TYPE_IMAGE_URL:
                $constraint = new Assert\Type(['type' => 'string']);
                break;
            case Param::TYPE_EMAIL:
                $constraint = new Assert\Email(['strict' => true]);
                break;
            case Param::TYPE_URL:
                $constraint = new Assert\Url();
                break;
            case Param::TYPE_PHONE:
                $constraint = new AssertPhoneNumber();
                break;
            case Param::TYPE_INT:
                $constraint = new Assert\Type(['type' => 'integer']);
                break;
            case Param::TYPE_DOUBLE:
                $constraint = new Assert\Type(['type' => 'double']);
                break;
            case Param::TYPE_BOOL:
                $constraint = new Assert\Type(['type' => 'boolean']);
                break;
        }

        $violations = $this->validator->validate($param->getValue(), $constraint);

        foreach ($violations as $violation) {
            $this->context
                ->buildViolation($violation->getMessage())
                ->atPath($this->field)
                ->addViolation();
        }
    }
}
