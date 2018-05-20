<?php

namespace App\DataFixtures;

use App\Entity\CallRequest;
use Darsyn\IP\IP;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\Generator\DataGenerator;
use libphonenumber\PhoneNumberUtil;

class CallRequestFixtures extends Fixture
{
    protected $phoneUtil;

    public function __construct(PhoneNumberUtil $phoneUtil)
    {
        $this->phoneUtil = $phoneUtil;
    }

    public function load(ObjectManager $manager)
    {
        $statuses = array_keys(CallRequest::$statuses);

        for ($i = 0; $i < 15; $i++) {
            $callRequest = new CallRequest();
            $callRequest
                ->setName(DataGenerator::getName())
                ->setCreatedAt(DataGenerator::generateDate(-5))
                ->setPhone($this->phoneUtil->parse(DataGenerator::generatePhone()))
                ->setIp(new IP(DataGenerator::generateIp()))
                ->setStatus($statuses[mt_rand(0, count(CallRequest::$statuses) - 1)]);

            $manager->persist($callRequest);
        }

        $manager->flush();
    }
}