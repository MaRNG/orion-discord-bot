<?php

namespace App\Util;

class StringFormatter
{
    public static function toCamelCase(string $input, string $separator = '_')
    {
        return lcfirst(str_replace($separator, '', ucwords($input, $separator)));
    }
}