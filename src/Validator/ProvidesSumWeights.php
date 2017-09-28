<?php

namespace Brammm\Vat\Validator;

trait ProvidesSumWeights
{
    protected function sumWeights(array $weights, string $vatNumber, int $start = 0): int
    {
        $checkval = 0;
        $count = count($weights);
        for ($i = $start; $i < $count; $i++) {
            $checkval += (int)$vatNumber[$i] * $weights[$i];
        }

        return $checkval;
    }
}
