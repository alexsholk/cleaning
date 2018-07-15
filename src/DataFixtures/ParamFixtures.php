<?php

namespace App\DataFixtures;

use App\DataFixtures\Generator\DataGenerator;
use App\Entity\Order;
use App\Entity\Param;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ParamFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $params = [];

        /** Настройки сайта */

        // Meta главной страницы
        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_SITE_SETTINGS)
            ->setTitle('Заголовок сайта')
            ->setCode('SITE_TITLE')
            ->setType(Param::TYPE_STRING)
            ->setValue('Уборка квартир в Минске');

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_SITE_SETTINGS)
            ->setTitle('Описание сайта (SEO)')
            ->setCode('SITE_META_DESCRIPTION')
            ->setType(Param::TYPE_STRING)
            ->setValue('Уборка квартир в Минске быстро и недорого.');

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_SITE_SETTINGS)
            ->setTitle('Ключевые слова (SEO)')
            ->setCode('SITE_META_KEYWORDS')
            ->setType(Param::TYPE_STRING)
            ->setValue('Уборка квартир, клининговый сервис');

        // Заголовок страницы заказа
        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_SITE_SETTINGS)
            ->setTitle('Заголовок страницы заказа')
            ->setCode('SITE_ORDER_TITLE')
            ->setType(Param::TYPE_STRING)
            ->setValue('Оформление заказа');

        // Телефон
        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_SITE_SETTINGS)
            ->setTitle('Телефон')
            ->setCode('PHONE')
            ->setType(Param::TYPE_PHONE)
            ->setValue('+375 29 196 28 29');

        // Социальные сети
        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_SITE_SETTINGS)
            ->setTitle('Ссылка на Facebook')
            ->setCode('LINK_FACEBOOK')
            ->setType(Param::TYPE_URL)
            ->setValue('https://www.facebook.com/');

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_SITE_SETTINGS)
            ->setTitle('Ссылка на VK')
            ->setCode('LINK_VK')
            ->setType(Param::TYPE_URL)
            ->setValue('https://vk.com/');

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_SITE_SETTINGS)
            ->setTitle('Ссылка на Instagram')
            ->setCode('LINK_INSTAGRAM')
            ->setType(Param::TYPE_URL)
            ->setValue('https://www.instagram.com/');

        // Слоган
        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_SITE_SETTINGS)
            ->setTitle('Слоган')
            ->setCode('SITE_SLOGAN')
            ->setType(Param::TYPE_STRING)
            ->setValue('Мы работаем, чтобы Вы отдыхали!');

        // Прочие настройки
        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_SITE_SETTINGS)
            ->setTitle('Разрешить добавление отзывов через сайт')
            ->setCode('REVIEW_ADD_ENABLED')
            ->setType(Param::TYPE_BOOL)
            ->setValue(true);

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_SITE_SETTINGS)
            ->setTitle('Телефон для СМС-уведомлений')
            ->setCode('SMS_NOTIFICATION_PHONE')
            ->setType(Param::TYPE_PHONE)
            ->setValue(null);

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_SITE_SETTINGS)
            ->setTitle('СМС-уведомление о создании заказа')
            ->setCode('ORDER_CREATED_SMS_NOTIFICATION')
            ->setType(Param::TYPE_BOOL)
            ->setValue(true);

        /** Текстовые блоки */

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_TEXT_BLOCKS)
            ->setTitle('Блок ссылок на главной')
            ->setCode('BLOCK_MAIN_LINKS')
            ->setType(Param::TYPE_HTML)
            ->setValue(<<<HTML
<ul>
    <li><a href="/page/maintenance-cleaning">Поддерживающая уборка</a></li>
    <li><a href="/page/spring-cleaning">Генеральная уборка</a></li>
    <li><a href="/page/cleaning-after-repair">Уборка после строительства и ремонта</a></li>
    <li><a href="/page/washing-windows">Мытье окон</a></li>
    <li><a href="/page/additional-services">Дополнительные услуги</a></li>
    <li><a href="/page/what-we-do-not-do" class="grey">Что мы не делаем</a></li>
</ul>            
HTML
            );

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_TEXT_BLOCKS)
            ->setTitle('Первый блок текста на главной')
            ->setCode('BLOCK_MAIN_TEXT_1')
            ->setType(Param::TYPE_HTML)
            ->setValue(
                '<h3>Клининговая компания “Уборочка” в Минске</h3><p>' .
                DataGenerator::generateText(25) . '</p>'
            );

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_TEXT_BLOCKS)
            ->setTitle('Второй блок текста на главной')
            ->setCode('BLOCK_MAIN_TEXT_2')
            ->setType(Param::TYPE_HTML)
            ->setValue(
                '<h3>Почему выбирают нас</h3><p>' .
                DataGenerator::generateText(25) . '</p>'
            );

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_TEXT_BLOCKS)
            ->setTitle('Нижнее меню')
            ->setCode('BLOCK_FOOTER_MENU')
            ->setType(Param::TYPE_HTML)
            ->setValue(<<<HTML
<ul>
    <li><a href="/page/faq">Вопросы и ответы</a></li>
    <li><a href="/page/vacancies">Хотите работать у нас?</a></li>
    <li><a href="/page/user-agreement">Пользовательское соглашение</a></li>
    <li><a href="/page/about">О сервисе</a></li>
</ul>
HTML
            );

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_TEXT_BLOCKS)
            ->setTitle('Скидки и акции')
            ->setCode('BLOCK_OFFERS')
            ->setType(Param::TYPE_HTML)
            ->setValue(DataGenerator::generateText(5, true));

        /** Сообщения */

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_MESSAGES)
            ->setTitle('Сообщение об успешном добавлении отзыва')
            ->setCode('REVIEW_ADD_SUCCESS_MESSAGE')
            ->setType(Param::TYPE_HTML)
            ->setValue(
                '<h3>Спасибо за отзыв</h3>' .
                '<p>Ваш отзыв сохранен и будет опубликован после проверки администратором.</p>'
            );

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_MESSAGES)
            ->setTitle('Сообщение об ошибке при добавлении отзыва')
            ->setCode('REVIEW_ADD_ERROR_MESSAGE')
            ->setType(Param::TYPE_HTML)
            ->setValue(
                '<h3>Произошла ошибка</h3>' .
                '<p>Произошла ошибка при сохранении отзыва. Скоро всё исправим.</p>'
            );

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_MESSAGES)
            ->setTitle('Сообщение об успешном запросе звонка')
            ->setCode('CALL_REQUEST_ADD_SUCCESS_MESSAGE')
            ->setType(Param::TYPE_HTML)
            ->setValue(
                '<h3>Запрос отправлен</h3>' .
                '<p>Мы скоро вам перезвоним.</p>'
            );

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_MESSAGES)
            ->setTitle('Сообщение об ошибке при запросе звонка')
            ->setCode('CALL_REQUEST_ADD_ERROR_MESSAGE')
            ->setType(Param::TYPE_HTML)
            ->setValue(
                '<h3>Произошла ошибка</h3>' .
                '<p>Произошла ошибка при отправке запроса. Скоро всё исправим.</p>'
            );

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_MESSAGES)
            ->setTitle('Сообщение об успешном добавлении заказа')
            ->setCode('ORDER_ADD_SUCCESS_MESSAGE')
            ->setType(Param::TYPE_HTML)
            ->setValue(
                '<h3>Спасибо за заказ</h3>' .
                '<p>Ваш заказ принят. Ожидайте звонка менеджера.</p>'
            );

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_MESSAGES)
            ->setTitle('Сообщение об ошибке при добавлении заказа')
            ->setCode('ORDER_ADD_ERROR_MESSAGE')
            ->setType(Param::TYPE_HTML)
            ->setValue(
                '<h3>Произошла ошибка</h3>' .
                '<p>Произошла ошибка при сохранении заказа. Скоро всё исправим.</p>'
            );

        /** Настройки заказов */

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_ORDER_SETTINGS)
            ->setTitle('Базовая стоимость заказа')
            ->setCode('ORDER_BASE_COST')
            ->setType(Param::TYPE_DOUBLE)
            ->setValue(20.0);

        // Скидка за периодичность
        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_ORDER_SETTINGS)
            ->setTitle('Скидка за периодичность: раз в месяц')
            ->setCode('FREQUENCY_DISCOUNT_'.Order::FREQUENCY_MONTHLY)
            ->setType(Param::TYPE_INT)
            ->setValue(10);

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_ORDER_SETTINGS)
            ->setTitle('Скидка за периодичность: раз в 2 недели')
            ->setCode('FREQUENCY_DISCOUNT_'.Order::FREQUENCY_EVERY_TWO_WEEKS)
            ->setType(Param::TYPE_INT)
            ->setValue(15);

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_ORDER_SETTINGS)
            ->setTitle('Скидка за периодичность: раз в неделю')
            ->setCode('FREQUENCY_DISCOUNT_'.Order::FREQUENCY_WEEKLY)
            ->setType(Param::TYPE_INT)
            ->setValue(20);

        // Время заказа
        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_ORDER_SETTINGS)
            ->setTitle('Минимальное время заказа')
            ->setCode('ORDER_MIN_TIME')
            ->setType(Param::TYPE_TIME)
            ->setValue('00:00');

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_ORDER_SETTINGS)
            ->setTitle('Максимальное время заказа')
            ->setCode('ORDER_MAX_TIME')
            ->setType(Param::TYPE_TIME)
            ->setValue('23:59');

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_ORDER_SETTINGS)
            ->setTitle('Минимальное время до уборки (минут)')
            ->setCode('ORDER_MIN_TIME_TO_CLEANING')
            ->setType(Param::TYPE_INT)
            ->setValue(240);

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_ORDER_SETTINGS)
            ->setTitle('Максимальное время до уборки (дней)')
            ->setCode('ORDER_MAX_TIME_TO_CLEANING')
            ->setType(Param::TYPE_INT)
            ->setValue(30);

        $params[] = (new Param())
            ->setCategory(Param::CATEGORY_ORDER_SETTINGS)
            ->setTitle('Интервал времени для списка в календаре')
            ->setCode('ORDER_TIME_STEP')
            ->setType(Param::TYPE_INT)
            ->setValue(60);

        // Сохранение параметров
        foreach ($params as $param) {
            $manager->persist($param);
            $this->addReference($param->getCode(), $param);
        }

        $manager->flush();
    }
}