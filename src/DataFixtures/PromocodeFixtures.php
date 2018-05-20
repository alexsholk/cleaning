<?php

namespace App\DataFixtures;

use App\DataFixtures\Generator\DataGenerator;
use App\Entity\Promocode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PromocodeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $promocodes   = [];
        $promocodes[] = (new Promocode())
            ->setEnabled(true)
            ->setCode('SUMMER')
            ->setDiscount(10)
            ->setQuantity(5);

        $promocodes[] = (new Promocode())
            ->setEnabled(true)
            ->setCode('J18')
            ->setDiscount(5)
            ->setStartDate(DataGenerator::generateDate(-30, -10))
            ->setEndDate(DataGenerator::generateDate(10, 30));

        foreach ($promocodes as $i => $promocode) {
            $manager->persist($promocode);
            $this->addReference('promocode' . $i, $promocode);
        }

        $manager->flush();
    }
}