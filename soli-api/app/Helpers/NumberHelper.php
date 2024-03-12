<?php

namespace App\Helpers;
class NumberHelper
{
    public function abbreviateNumber($number): string
    {
        $suffixes = ['', 'K', 'M', 'B', 'T']; // Tiền tố số
        $index = 0;

        while ($number >= 1000) {
            $number /= 1000;
            $index++;
        }

        return round($number, 2) . $suffixes[$index];
    }
}
