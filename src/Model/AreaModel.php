<?php

namespace App\Model;

class AreaModel
{
    protected static $areas = [
        'Заводской',
        'Ленинский',
        'Московский',
        'Октябрьский',
        'Партизанский',
        'Первомайский',
        'Советский',
        'Фрунзенский',
        'Центральный',
    ];

    public static function getAll(): array
    {
        return self::$areas;
    }

    public static function get(int $i): ?string
    {
        return self::$areas[$i] ?? null;
    }
}