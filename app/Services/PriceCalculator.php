<?php

namespace App\Services;

class PriceCalculator
{
    public static function calculate(int $subTotal): array
    {
        $step = 500;

        $pb1 = (int) round($subTotal * 0.10);
        $service = 0; // (int) round($subTotal * 0.01)

        $gross = $subTotal + $pb1 + $service;
        $rounding = $gross % $step;
        $total = $gross - $rounding;

        return [
            // 'subTotal' => $subTotal,
            'otherCosts' => $pb1 + $service,
            'pb1' => $pb1,
            // 'serviceCharge' => $service,
            'rounding' => $rounding,
            'total' => $total,
        ];
    }
}
