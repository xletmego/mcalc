<?php

namespace App\Data;

class Functions
{
    public static function nr1 (float $param1, float $param2, float $param3): float
    {
        return (9.99*$param1) + (6.25 * $param2) + (4.92 * $param3) + 5;
    }

    public static function nr2 (float $param1, float $param2, float $param3): float
    {
        return (9.99 * $param1) + (6.25 * $param2) + (4.92 * $param3) - 161;
    }

    public static function nr3 (float $param1, float $param2): float
    {
        return $param1 + (15*0.92 * $param2 * 9.3);
    }
}