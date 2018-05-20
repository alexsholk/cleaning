<?php

namespace App\DataFixtures;

use App\DataFixtures\Generator\DataGenerator;
use App\Entity\Cleaner;
use App\Entity\Inventory;
use App\Entity\InventoryMovement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class InventoryMovementFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $cleanerCount = CleanerFixtures::COUNT;
        $inventoryCount = count(InventoryFixtures::$names);

        // Закупка
        for ($i = 0; $i < $inventoryCount; $i++) {
            /** @var Inventory $inventory */
            $inventory = $this->getReference('inventory' . $i);
            $movement = new InventoryMovement();
            $movement
                ->setInventory($inventory)
                ->setType(InventoryMovement::TYPE_PURCHASE)
                ->setDatetime(DataGenerator::generateDate(-20, -15))
                ->setQuantity($inventory->getStep() * mt_rand(20, 50));

            $manager->persist($movement);
        }

        // Выдача/возврат/списание
        for ($i = 0; $i < $cleanerCount; $i++) {
            /** @var Cleaner $cleaner */
            $cleaner = $this->getReference('cleaner' . $i);
            /** @var Inventory $inventory */
            $inventory = $this->getReference('inventory' . mt_rand(0, $inventoryCount - 1));
            // Выдача
            $movement = new InventoryMovement();
            $movement
                ->setInventory($inventory)
                ->setCleaner($cleaner)
                ->setType(InventoryMovement::TYPE_PROVIDE)
                ->setDatetime(DataGenerator::generateDate(-14, -12))
                ->setQuantity($inventory->getStep() * 3);

            $manager->persist($movement);

            // Возврат
            $movement = new InventoryMovement();
            $movement
                ->setInventory($inventory)
                ->setCleaner($cleaner)
                ->setType(InventoryMovement::TYPE_RETURN)
                ->setDatetime(DataGenerator::generateDate(-11, -10))
                ->setQuantity($inventory->getStep() * 2);

            $manager->persist($movement);

            // Списание
            $movement = new InventoryMovement();
            $movement
                ->setInventory($inventory)
                ->setCleaner($cleaner)
                ->setType(InventoryMovement::TYPE_CONSUMPTION)
                ->setDatetime(DataGenerator::generateDate(-9, -5))
                ->setQuantity($inventory->getStep());

            $manager->persist($movement);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            InventoryFixtures::class,
            CleanerFixtures::class,
        ];
    }
}