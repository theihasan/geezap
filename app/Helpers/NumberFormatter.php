<?php

namespace App\Helpers;

class NumberFormatter
{
    public static function formatNumber($number): string
    {
        return match (true) {
            $number >= 1000000000 => round($number / 1000000000, 1) . 'B',
            $number >= 1000000 => round($number / 1000000, 1) . 'M',
            $number >= 1000 => round($number / 1000, 1) . 'K',
            default => (string) $number,
        };
    }
}
