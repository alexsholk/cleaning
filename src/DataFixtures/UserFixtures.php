<?php

namespace App\DataFixtures;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setUsername('admin')
            ->setEmail('example@gmail.com')
            ->setPassword($this->encoder->encodePassword($user1, 'admin'))
            ->setRoles(['ROLE_SUPER_ADMIN'])
            ->setEnabled(true);

        $manager->persist($user1);
        $manager->flush();
    }
}