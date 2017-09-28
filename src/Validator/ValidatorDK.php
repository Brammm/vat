<?php

namespace Brammm\Vat\Validator;

use function Brammm\Vat\sumWeights;

/**
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8]
 *
 * Range:
 *      C1 > 0
 *
 * Rules:
 * R
 *      R = (2*C1 + 7*C2 + 6*C3 + 5*C4 + 4*C5 + 3*C6 + 2*C7 + C8)
 *
 *      R is divisible by 11
 */
class ValidatorDK implements Validator
{
    /**
     * @inheritdoc
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 8) {
            return false;
        }
        $weights = [2, 7, 6, 5, 4, 3, 2, 1];
        $checksum = sumWeights($weights, $vatNumber);

        if (($checksum % 11) > 0) {
            return false;
        }

        return true;
    }
}
