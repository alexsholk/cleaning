<?php

namespace App\DataFixtures;

use App\DataFixtures\Generator\DataGenerator;
use App\Entity\Cleaner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use libphonenumber\PhoneNumberUtil;

class CleanerFixtures extends Fixture
{
    protected $phoneUtil;

    public function __construct(PhoneNumberUtil $phoneUtil)
    {
        $this->phoneUtil = $phoneUtil;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $cleaner = new Cleaner();
            $cleaner
                ->setName(DataGenerator::getFemaleName())
                ->setPhone($this->phoneUtil->parse(DataGenerator::generatePhone()))
                ->setAdditionalPhone($this->phoneUtil->parse(DataGenerator::generatePhone()));

            $this->addReference('cleaner' . ($i + 1), $cleaner);
            $manager->persist($cleaner);
        }

        $manager->flush();
    }
}