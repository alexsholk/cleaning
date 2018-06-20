<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Pluralize extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('pluralize', [$this, 'pluralize']),
        ];
    }

    public function pluralize($count, array $forms)
    {
        $index = ($count % 10 == 1 && $count % 100 != 11 ? 0 : $count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20) ? 1 : 2);
        return $forms[$index] ?? '';
    }
}