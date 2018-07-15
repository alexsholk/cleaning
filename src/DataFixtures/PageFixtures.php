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
            ->setTitle('Поддерживающая уборка')
            ->setSiteTitle('Поддерживающая уборка')
            ->setSlug('maintenance-cleaning')
            ->setMetaDescription('Заказать домашнюю уборку в Минске')
            ->setMetaKeywords('Уборка на дому, заказать уборку в Минске')
            ->setContent(DataGenerator::generateText(30))
            ->setVisible(true);

        $pages[] = (new Page())
            ->setTitle('Генеральная уборка')
            ->setSiteTitle('Генеральная уборка')
            ->setSlug('spring-cleaning')
            ->setMetaDescription('Заказать домашнюю уборку в Минске')
            ->setMetaKeywords('Уборка на дому, заказать уборку в Минске')
            ->setContent(DataGenerator::generateText(30))
            ->setVisible(true);

        $pages[] = (new Page())
            ->setTitle('Уборка после строительства и ремонта')
            ->setSiteTitle('Уборка после строительства и ремонта')
            ->setSlug('cleaning-after-repair')
            ->setMetaDescription('Заказать домашнюю уборку в Минске')
            ->setMetaKeywords('Уборка на дому, заказать уборку в Минске')
            ->setContent(DataGenerator::generateText(30))
            ->setVisible(true);

        $pages[] = (new Page())
            ->setTitle('Мытье окон')
            ->setSiteTitle('Мытье окон')
            ->setSlug('washing-windows')
            ->setMetaDescription('Заказать домашнюю уборку в Минске')
            ->setMetaKeywords('Уборка на дому, заказать уборку в Минске')
            ->setContent(DataGenerator::generateText(30))
            ->setVisible(true);

        $pages[] = (new Page())
            ->setTitle('Дополнительные услуги')
            ->setSiteTitle('Дополнительные услуги')
            ->setSlug('additional-services')
            ->setMetaDescription('Заказать домашнюю уборку в Минске')
            ->setMetaKeywords('Уборка на дому, заказать уборку в Минске')
            ->setContent(DataGenerator::generateText(30))
            ->setVisible(true);

        $pages[] = (new Page())
            ->setTitle('Что мы не делаем')
            ->setSiteTitle('Что мы не делаем')
            ->setSlug('what-we-do-not-do')
            ->setMetaDescription('Заказать домашнюю уборку в Минске')
            ->setMetaKeywords('Уборка на дому, заказать уборку в Минске')
            ->setContent(DataGenerator::generateText(30))
            ->setVisible(true);

        // Footer links

        $pages[] = (new Page())
            ->setTitle('Вопросы и ответы')
            ->setSiteTitle('Вопросы и ответы')
            ->setSlug('faq')
            ->setMetaDescription('Заказать домашнюю уборку в Минске')
            ->setMetaKeywords('Уборка на дому, заказать уборку в Минске')
            ->setContent(DataGenerator::generateText(30))
            ->setVisible(true);

        $pages[] = (new Page())
            ->setTitle('Хотите работать у нас?')
            ->setSiteTitle('Хотите работать у нас?')
            ->setSlug('vacancies')
            ->setMetaDescription('Заказать домашнюю уборку в Минске')
            ->setMetaKeywords('Уборка на дому, заказать уборку в Минске')
            ->setContent(DataGenerator::generateText(30))
            ->setVisible(true);

        $pages[] = (new Page())
            ->setTitle('Пользовательское соглашение')
            ->setSiteTitle('Пользовательское соглашение')
            ->setSlug('user-agreement')
            ->setMetaDescription('Заказать домашнюю уборку в Минске')
            ->setMetaKeywords('Уборка на дому, заказать уборку в Минске')
            ->setContent(DataGenerator::generateText(30))
            ->setVisible(true);

        $pages[] = (new Page())
            ->setTitle('О сервисе')
            ->setSiteTitle('О сервисе')
            ->setSlug('about')
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