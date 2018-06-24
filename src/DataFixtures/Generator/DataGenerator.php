<?php

namespace App\DataFixtures\Generator;

use Behat\Transliterator\Transliterator;

class DataGenerator
{
    protected static $phoneCodes = ['25', '29', '33', '44'];

    protected static $maleNames = [
        'Александр',
        'Алексей',
        'Андрей',
        'Антон',
        'Артём',
        'Борис',
        'Вадим',
        'Виктор',
        'Виталий',
        'Владимир',
        'Глеб',
        'Григорий',
        'Дмитрий',
        'Денис',
        'Евгений',
        'Егор',
        'Иван',
        'Игорь',
        'Константин',
        'Кирилл',
        'Леонид',
        'Максим',
        'Никита',
        'Николай',
        'Олег',
        'Павел',
        'Роман',
        'Сергей',
        'Семён',
        'Степан',
        'Фёдор',
        'Филипп',
        'Юрий',
        'Ярослав',
    ];

    protected static $femaleNames = [
        'Александра',
        'Анастасия',
        'Алёна',
        'Анна',
        'Алина',
        'Арина',
        'Василиса',
        'Вера',
        'Виктория',
        'Дина',
        'Дарья',
        'Екатерина',
        'Елена',
        'Зоя',
        'Ирина',
        'Карина',
        'Кристина',
        'Лариса',
        'Людмила',
        'Мария',
        'Марина',
        'Наталья',
        'Оксана',
        'Олеся',
        'Ольга',
        'Полина',
        'Раиса',
        'Сабина',
        'Светлана',
        'Соня',
        'Татьяна',
        'Ульяна',
        'Фаина',
        'Юлия',
        'Яна',
        'Ярина',
    ];

    protected static $sampleText = <<<TEXT
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam eget feugiat nisi. Morbi sollicitudin elit nec maximus 
faucibus. Suspendisse a urna id magna consequat auctor vel et elit. Phasellus vehicula enim et augue aliquam, sit amet 
egestas justo maximus. Sed non magna vel massa hendrerit cursus sit amet non lectus. Cras molestie, ex at congue 
pretium, elit diam congue eros, ac imperdiet leo urna sit amet augue. Pellentesque tempor est nulla, id malesuada neque 
viverra ac. In rutrum condimentum nunc sit amet dictum. Pellentesque vestibulum magna ut rhoncus porta. Vestibulum 
dignissim fringilla rutrum. Nulla ac convallis augue. Donec ut feugiat dolor, sit amet iaculis ante.
Maecenas euismod tempor eros. Cras ornare tempor facilisis. Duis magna nunc, viverra gravida sodales at, laoreet vitae 
justo. Nam auctor mi nec velit aliquam, eget ullamcorper mauris egestas. Lorem ipsum dolor sit amet, consectetur 
adipiscing elit. Suspendisse in dolor feugiat, imperdiet risus ut, mollis sapien. Maecenas in ultrices orci. Etiam id 
fermentum urna, non pharetra ipsum. Duis nec volutpat metus.
In hac habitasse platea dictumst. Morbi ultricies metus ut lectus scelerisque, eget sagittis augue tincidunt. Nam 
facilisis, erat sit amet sagittis pulvinar, risus nunc tempus sem, in mollis dui tortor eget enim. Proin sagittis quam 
vel erat mollis tempus. Phasellus gravida nisl id leo malesuada, nec imperdiet elit sollicitudin. Etiam non scelerisque 
libero. Sed tincidunt tempus semper. Cras a quam orci. Vestibulum ipsum enim, accumsan vulputate quam quis, pretium 
rhoncus erat. Nullam porta luctus ligula, sed pulvinar massa ultrices et.
Integer in dolor orci. Sed molestie augue eget ultrices mollis. Proin ornare lorem sapien, vitae suscipit arcu mattis 
auctor. Phasellus mollis nunc quis nibh posuere, id lacinia urna condimentum. In vestibulum facilisis dignissim. Proin 
consectetur enim quis augue pretium, sed convallis neque porttitor. Ut varius, purus a maximus tempus, diam massa cursus 
urna, at facilisis lectus nisi ac ex. Aliquam ut suscipit ex.
Phasellus laoreet tempus semper. Praesent quis orci lorem. Donec et aliquam neque. Donec pellentesque est sed arcu 
gravida, vitae feugiat dolor placerat. In a purus viverra, cursus felis pellentesque, hendrerit ante. Praesent fermentum 
tellus felis, at consectetur tellus imperdiet ac. Curabitur tincidunt lacus quis lorem luctus, eu tempus justo 
condimentum. Vestibulum accumsan finibus rutrum. Vivamus tincidunt turpis nec mauris euismod aliquam. Phasellus 
convallis, nisl a ornare dapibus, nulla ligula consequat metus, sit amet tempus est elit vitae enim. Suspendisse a 
fermentum orci. Vivamus lobortis convallis elit. Sed scelerisque, enim et porttitor sollicitudin, risus erat iaculis 
erat, et viverra tellus nulla eu tellus. Ut rhoncus quis odio quis pulvinar. Phasellus sit amet faucibus ante.
TEXT;

    /**
     * @return string
     */
    public static function generatePhone(): string
    {
        $code = self::$phoneCodes[mt_rand(0, count(self::$phoneCodes) - 1)];
        return '+375-' . $code . '-' . rand(100, 999) . '-' . rand(10, 99) . '-' . rand(10, 99);
    }

    /**
     * @param int $min
     * @param int $max
     * @return \DateTime
     * @throws \Exception
     */
    public static function generateDate($min = -60, $max = 0): \DateTime
    {
        $days = rand($min, $max);
        $interval = new \DateInterval('P' . abs($days) . 'D');
        $method = $days < 0 ? 'sub' : 'add';

        return (new \DateTime())->$method($interval)->setTime(rand(8, 20), rand(0, 59));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        $names = array_merge(self::$maleNames, self::$femaleNames);
        return $names[mt_rand(0, count($names) - 1)];
    }

    /**
     * @return string
     */
    public static function getMaleName(): string
    {
        return self::$maleNames[mt_rand(0, count(self::$maleNames) - 1)];
    }

    /**
     * @return string
     */
    public static function getFemaleName(): string
    {
        return self::$femaleNames[mt_rand(0, count(self::$femaleNames) - 1)];
    }

    /**
     * @param int $sentencesCount
     * @param bool $html
     * @return string
     */
    public static function generateText(int $sentencesCount, $html = false): string
    {
        $text = '';
        $sentences = preg_split('/(?<=[.?!])\s+(?=[a-z])/i', self::$sampleText);

        for ($i = 0; $i < $sentencesCount; $i++) {
            $sentence = $sentences[mt_rand(0, count($sentences) - 1)];

            if ($html) {
                $sentence = '<p>' . $sentence . '</p>';
            }
            $text .= $sentence . "\n";
        }

        return $text;
    }

    /**
     * @param null|string $name
     * @return string
     */
    public static function generateEmail(?string $name = null): string
    {
        if (!$name) {
            $name = self::getName();
        }

        $email = Transliterator::transliterate($name);
        $email .= mt_rand(10, 99) . '@example.com';
        return $email;
    }

    /**
     * @return string
     */
    public static function generateIp(): string
    {
        return '203.0.' . mt_rand(0, 255) . '.' . mt_rand(0, 255);
    }
}