<?php

namespace Brammm\Vat\Validator;

trait ProvidesCrossSum
{
    protected function crossSum(string $val): int
    {
        $sum = 0;
        $count = strlen($val);
        for ($i = 0; $i < $count; $i++) {
            $sum += (int)$val[$i];
        }

        return $sum;
    }
}
