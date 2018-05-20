<?php

namespace App\Validator\Constraints;

use Darsyn\IP\IP;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

class AntiSpamValidator extends ConstraintValidator
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof AntiSpam) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\AntiSpam');
        }

        if (null === $value) {
            return;
        }

        $em = $this->registry->getManagerForClass(get_class($value));

        if (!$em) {
            $message = sprintf(
                'Unable to find the object manager associated with an entity of class "%s".',
                get_class($value)
            );

            throw new ConstraintDefinitionException($message);
        }

        $datetime = new \DateTime();
        $datetime->sub(new \DateInterval('PT'.$constraint->interval.'M'));

        $criteria = new Criteria();
        $criteria
            ->where($criteria->expr()->eq($constraint->ipField, new IP($_SERVER['REMOTE_ADDR'])))
            ->andWhere($criteria->expr()->gte($constraint->dateField, $datetime))
            ->setMaxResults(1);

        /** @var EntityRepository $repository */
        $repository = $em->getRepository(get_class($value));

        try {
            $result = $repository
                ->createQueryBuilder('e')
                ->select('e.'.$constraint->dateField)
                ->addCriteria($criteria)
                ->orderBy('e.'.$constraint->dateField, 'DESC')
                ->getQuery()
                ->getSingleResult();

            /** @var \DateTime $lastDate */
            $lastDate = $result[$constraint->dateField];
            $diff     = $constraint->interval - round(((new \DateTime())->getTimestamp() - $lastDate->getTimestamp()) / 60);

            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ time }}', $diff)
                ->setPlural($diff)
                ->addViolation();
        } catch (NoResultException $e) {
            return;
        }
    }
}