<?php

namespace App\Util;

class NumberFormatter
{
    public static function format(int|float $number): string
    {
        return number_format($number, 0, '.', ' ');
    }
}