<?php

namespace App\Tools;

class ChoicesHelper
{
    /**
     * Генерация списка значений
     *
     * @param int|double $start - начальное значение
     * @param int|double $end - конечное значение
     * @param int|double $step - шаг
     * @param array $forms - формы слова для единственного и множественного числа
     *
     * @return array
     */
    public static function generateChoices($start, $end, $step, array $forms)
    {
        $choices = [];
        for ($i = $start; $i <= $end; $i += $step) {
            $i               = round($i, 1);
            $title           = $i . ' ' . PluralizationHelper::pluralize($i, $forms, 'ru');
            $choices[$title] = $i;
        }

        return $choices;
    }

    /**
     * Список значений времени
     *
     * @param string $startTime - начальное время
     * @param string $endTime - конечное время
     * @param int $step - шаг в минутах
     *
     * @return array
     */
    public static function generateTimeList($startTime, $endTime, $step)
    {
        $timeList = [];
        $start    = \DateTime::createFromFormat('H:i', $startTime);
        $end      = \DateTime::createFromFormat('H:i', $endTime);
        $current  = clone($start);

        while ($current <= $end) {
            $timeList[] = $current->format('H:i');
            $current->modify("+ $step minutes");
            if ($current->format('H:i') === $start->format('H:i')) {
                break;
            }
        }

        return $timeList;
    }
}