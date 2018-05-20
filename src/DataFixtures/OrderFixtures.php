<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\DataFixtures\Generator\DataGenerator;
use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use libphonenumber\PhoneNumberUtil;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    protected $phoneUtil;

    public function __construct(PhoneNumberUtil $phoneUtil)
    {
        $this->phoneUtil = $phoneUtil;
    }

    public function load(ObjectManager $manager)
    {
        $currentDate = new \DateTime();
        $baseCost = $this->getReference('ORDER_BASE_COST')->getValue();

        for ($i = 0; $i < 25; $i++) {
            $date = DataGenerator::generateDate(-14, 14);

            $frequency = array_rand(Order::$frequencies);
            $discountFrequency = 0;
            if ($frequency !== Order::FREQUENCY_ONCE) {
                $discountFrequency = $this->getReference('FREQUENCY_DISCOUNT_' . $frequency)->getValue();
            }

            $order = new Order();
            $order->setDatetime($date)
                ->setStatus($date < $currentDate ? Order::STATUS_COMPLETE : Order::STATUS_NEW)
                ->setBaseCost($baseCost)
                ->setFrequency($frequency)
                ->setDiscountFrequency($discountFrequency)
                ->setPromocode($this->getReference('promocode' . $i % 2))
                ->setName(DataGenerator::getName())
                ->setPhone($this->phoneUtil->parse(DataGenerator::generatePhone()))
                ->setCity(Order::DEFAULT_CITY)
                ->setStreet($this->getReference('street' . $i % 10))
                ->setHome(rand(1,100))
                ->setFlat(rand(1,240));

            /**
             * Обязательные услуги
             * @var Service $serviceRoom
             * @var Service $serviceBathroom
             */
            $serviceRoom= $this->getReference('service0');
            $serviceBathroom = $this->getReference('service1');
            $order->addService($serviceRoom, rand(1, 5));
            $order->addService($serviceBathroom, rand(1, 2));

            // Необязательные услуги
            for ($j = 2; $j < 10; $j++) {
                // В среднем 1/3 услуг добавится к заказу
                if (rand(0, 2)) continue;

                /** @var Service $service */
                $service = $this->getReference('service'.$j);
                $order->addService($service, rand(1, 5));
            }

            $manager->persist($order);
        }
    }

    public function getDependencies()
    {
        return [
            ServiceFixtures::class,
            PromocodeFixtures::class,
            StreetFixtures::class,
        ];
    }
}