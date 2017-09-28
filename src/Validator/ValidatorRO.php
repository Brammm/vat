<?php

namespace Brammm\Vat\Validator;

use function Brammm\Vat\sumWeights;

/**
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10]
 *
 * Range:
 *      C1 ... C10 Numeric
 *      Note that if the length is less than 10 digits, leading zeros must be assumed to perform the computation.
 *
 * Rules:
 * C10
 *      A1 = C1*7 + C2*5 + C3*3 + C4*2 + C5*1 + C6*7 + C7*5 + C8*3 + C9*2
 *      A2 = A1 * 10
 *      R1 = A2 modulo 11
 *      If R1 = 10, then R = 0
 *      Else R = R1
 *      C10 = R
 */
class ValidatorRO implements VatNumberValidator
{
    /**
     * @inheritdoc
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) < 2 || strlen($vatNumber) > 10) {
            return false;
        }

        $vatNumber = str_pad($vatNumber, 10, "0", STR_PAD_LEFT);

        $checksum = (int)$vatNumber[9];
        $weights = [7, 5, 3, 2, 1, 7, 5, 3, 2];
        $checkval = sumWeights($weights, $vatNumber);

        $checkval = ($checkval * 10) % 11;
        if ($checkval == 10) {
            $checkval = 0;
        }

        if ($checkval != $checksum) {
            return false;
        }

        return true;
    }
}
