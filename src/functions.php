<?php

namespace Brammm\Vat;

if (! function_exists('Brammm\Vat\crossSum')) {
    function crossSum(string $val): int
    {
        $sum = 0;
        $count = strlen($val);
        for ($i = 0; $i < $count; $i++) {
            $sum += (int)$val[$i];
        }

        return $sum;
    }
}

if (! function_exists('Brammm\Vat\isEven')) {
    function isEven(int $val): bool
    {
        return ($val / 2 == floor($val / 2)) ? true : false;
    }
}

if (! function_exists('Brammm\Vat\sumWeights')) {
    function sumWeights(array $weights, string $vatNumber, int $start = 0): int
    {
        $checkval = 0;
        $count = count($weights);
        for ($i = $start; $i < $count; $i++) {
            $checkval += (int)$vatNumber[$i] * $weights[$i];
        }

        return $checkval;
    }
}

