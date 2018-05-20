<?php

namespace App\Tools;

use Symfony\Component\Translation\PluralizationRules;

class PluralizationHelper
{
    /**
     * Множественная форма
     *
     * @param $n - количество
     * @param array $forms - формы слова
     * @param string $locale
     *
     * @return mixed|null
     */
    public static function pluralize($n, array $forms, $locale)
    {
        $i = PluralizationRules::get($n, $locale);

        return isset($forms[$i]) ? $forms[$i] : (isset($forms[0]) ? $forms[0] : null);
    }
}