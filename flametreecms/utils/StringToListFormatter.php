<?php

namespace GodSpeed\FlametreeCMS\Utils\Formatter;

class StringToListFormatter
{

    public static function parse($string, $delimter = ",")
    {
        $trimContent = str_replace(" ", "", $string);
        $splitContent = explode($delimter, $trimContent);

        return $splitContent;
    }
}
