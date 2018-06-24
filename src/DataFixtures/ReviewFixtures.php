<?php

namespace App\DataFixtures;

use App\DataFixtures\Generator\DataGenerator;
use App\Entity\Review;
use Darsyn\IP\IP;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ReviewFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $review = (new Review())
                ->setName(DataGenerator::getName())
                ->setCreatedAt(DataGenerator::generateDate(-60))
                ->setIp(new IP(DataGenerator::generateIp()))
                ->setText(DataGenerator::generateText(3))
                ->setWeight($i * 10)
                ->setVisible(true);

            $manager->persist($review);
        }

        $manager->flush();
    }
}