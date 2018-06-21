<?php

namespace App\DataFixtures;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ServiceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $services = [];
        $services[] = (new Service())
            ->setTitle('Комната')
            ->setShortCode('LR')
            ->setCode(ServiceRepository::SERVICE_ROOM)
            ->setPrice(14.0)
            ->setUnit('комната|комнаты|комнат')
            ->setAvailable(true)
            ->setWeight(10)
            ->setCountable(true)
            ->setMinCount(1)
            ->setMaxCount(10)
            ->setStep(1)
            ->setImageFile(null)
        ;

        $services[] = (new Service())
            ->setTitle('Санузел')
            ->setShortCode('BR')
            ->setCode(ServiceRepository::SERVICE_BATHROOM)
            ->setPrice(15.0)
            ->setUnit('санузел|санузла|санузлов')
            ->setAvailable(true)
            ->setWeight(20)
            ->setCountable(true)
            ->setMinCount(1)
            ->setMaxCount(4)
            ->setStep(1)
            ->setImageFile(null)
        ;

        $services[] = (new Service())
            ->setTitle('Внутри холодильника')
            ->setShortCode('FR')
            ->setCode(null)
            ->setPrice(12.0)
            ->setUnit('шт')
            ->setAvailable(true)
            ->setWeight(30)
            ->setCountable(true)
            ->setMinCount(1)
            ->setMaxCount(3)
            ->setStep(1)
            ->setImageFile($this->copyAndGet('icon-1.png'))
        ;

        $services[] = (new Service())
            ->setTitle('Внутри духовки')
            ->setShortCode('OV')
            ->setCode(null)
            ->setPrice(18.0)
            ->setUnit('шт')
            ->setAvailable(true)
            ->setWeight(40)
            ->setCountable(false)
            ->setMinCount(null)
            ->setMaxCount(null)
            ->setStep(null)
            ->setImageFile($this->copyAndGet('icon-2.png'))
        ;

        $services[] = (new Service())
            ->setTitle('Внутри кухонных шкафов')
            ->setShortCode('KC')
            ->setCode(null)
            ->setPrice(18.0)
            ->setUnit('шт')
            ->setAvailable(true)
            ->setWeight(50)
            ->setCountable(false)
            ->setMinCount(null)
            ->setMaxCount(null)
            ->setStep(null)
            ->setImageFile($this->copyAndGet('icon-3.png'))
        ;

        $services[] = (new Service())
            ->setTitle('Помоем посуду')
            ->setShortCode('DI')
            ->setCode(null)
            ->setPrice(9.0)
            ->setUnit('ч')
            ->setAvailable(true)
            ->setWeight(60)
            ->setCountable(false)
            ->setMinCount(null)
            ->setMaxCount(null)
            ->setStep(null)
            ->setImageFile($this->copyAndGet('icon-4.png'))
        ;

        $services[] = (new Service())
            ->setTitle('Внутри микроволновки')
            ->setShortCode('MI')
            ->setCode(null)
            ->setPrice(12.0)
            ->setUnit('шт')
            ->setAvailable(true)
            ->setWeight(70)
            ->setCountable(false)
            ->setMinCount(null)
            ->setMaxCount(null)
            ->setStep(null)
            ->setImageFile($this->copyAndGet('icon-5.png'))
        ;

        $services[] = (new Service())
            ->setTitle('Погладим белье')
            ->setShortCode('IR')
            ->setCode(null)
            ->setPrice(18.0)
            ->setUnit('ч')
            ->setAvailable(true)
            ->setWeight(80)
            ->setCountable(true)
            ->setMinCount(1)
            ->setMaxCount(5)
            ->setStep(0.5)
            ->setImageFile($this->copyAndGet('icon-6.png'))
        ;

        $services[] = (new Service())
            ->setTitle('Помоем окна')
            ->setShortCode('WI')
            ->setCode(null)
            ->setPrice(11.9)
            ->setUnit('шт')
            ->setAvailable(true)
            ->setWeight(90)
            ->setCountable(true)
            ->setMinCount(1)
            ->setMaxCount(20)
            ->setStep(1)
            ->setImageFile($this->copyAndGet('icon-7.png'))
        ;

        $services[] = (new Service())
            ->setTitle('Уберем на балконе')
            ->setShortCode('BA')
            ->setCode(null)
            ->setPrice(24.0)
            ->setUnit('шт')
            ->setAvailable(true)
            ->setWeight(100)
            ->setCountable(true)
            ->setMinCount(1)
            ->setMaxCount(4)
            ->setStep(1)
            ->setImageFile($this->copyAndGet('icon-8.png'))
        ;

        $services[] = (new Service())
            ->setTitle('Внутри шкафа')
            ->setShortCode('IC')
            ->setCode(null)
            ->setPrice(12.0)
            ->setUnit('шт')
            ->setAvailable(true)
            ->setWeight(110)
            ->setCountable(true)
            ->setMinCount(1)
            ->setMaxCount(10)
            ->setStep(1)
            ->setImageFile($this->copyAndGet('icon-9.png'))
        ;

        $services[] = (new Service())
            ->setTitle('Привезем ключи')
            ->setShortCode('KD')
            ->setCode(null)
            ->setPrice(2.0)
            ->setUnit(null)
            ->setAvailable(true)
            ->setWeight(120)
            ->setCountable(false)
            ->setMinCount(null)
            ->setMaxCount(null)
            ->setStep(null)
            ->setImageFile($this->copyAndGet('icon-10.png'))
        ;

        foreach ($services as $i => $service) {
            $manager->persist($service);
            $this->addReference('service' . $i, $service);
        }

        $manager->flush();
    }

    protected function copyAndGet($filename)
    {
        $imagesDir = realpath(__DIR__ . '/images/services') . '/';
        if (!$imagesDir) {
            throw new \Exception('Image directory not found.');
        }

        $tempDir = sys_get_temp_dir() . '/';

        if (!copy($imagesDir . $filename, $tempDir . $filename)) {
            throw new \Exception('Failed to copy file ' . $imagesDir . $filename);
        }

        return new UploadedFile($tempDir . $filename, $filename, null, null, null, true);
    }
}