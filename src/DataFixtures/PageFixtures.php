<?php

namespace App\DataFixtures;

use App\DataFixtures\Generator\DataGenerator;
use App\Entity\Page;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PageFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $pages = [];

        $pages[] = (new Page())
            ->setTitle('О сервисе')
            ->setSiteTitle('О сервисе')
            ->setSlug('about')
            ->setMetaDescription('Заказать домашнюю уборку в Минске')
            ->setMetaKeywords('Уборка на дому, заказать уборку в Минске')
            ->setContent(DataGenerator::generateText(30))
            ->setVisible(true);

        $pages[] = (new Page())
            ->setTitle('Оплата')
            ->setSiteTitle('Оплата')
            ->setSlug('payment')
            ->setMetaDescription('Заказать домашнюю уборку в Минске')
            ->setMetaKeywords('Уборка на дому, заказать уборку в Минске')
            ->setContent(DataGenerator::generateText(30))
            ->setVisible(true);

        foreach ($pages as $page) {
            $manager->persist($page);
        }

        $manager->flush();
    }
}