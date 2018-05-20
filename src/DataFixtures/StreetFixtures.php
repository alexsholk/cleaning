<?php

namespace App\DataFixtures;

use App\Entity\Street;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

class StreetFixtures extends Fixture
{
    protected $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function load(ObjectManager $manager)
    {
        $path = $this->kernel->getRootDir() . '/../streets.yml';
        if (!file_exists($path)) {
            return;
        }

        $names = Yaml::parse(file_get_contents($path));

        $refNumber = 0;
        foreach ($names as $i => $name) {
            $street = new Street();
            $street->setTitle($name);
            $manager->persist($street);

            // Каждая 100-я улица доступна для других fixtures
            if (($i + 1) % 100 == 0) {
                $this->addReference('street' . ++$refNumber, $street);
            }
        }

        $manager->flush();
    }
}