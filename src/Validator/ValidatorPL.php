<?php

namespace Brammm\Vat\Validator;

use function Brammm\Vat\sumWeights;

/**
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10]
 *
 * Range:
 *      C1 ... C9 Numeric from 0 to 9
 *
 * Rules:
 * C10
 *      A1 = 6*C1 + 5*C2 + 7*C3 + 2*C4 + 3*C5 + 4*C6 + 5*C7 + 6*C8 + 7*C9
 *      R = A1 modulo 11
 *      If R = 10, then VAT number is invalid
 *      C10 = R
 */
class ValidatorPL implements Validator
{
    use CountryCodeValidator;

    /**
     * @inheritdoc
     */
    public function getCountryCode(): string
    {
        return 'PL';
    }

    /**
     * @inheritdoc
     */
    protected function isValid(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 10) {
            return false;
        }

        $weights = [6, 5, 7, 2, 3, 4, 5, 6, 7];
        $checksum = (int)$vatNumber[9];
        $checkval = sumWeights($weights, $vatNumber);

        if ($checkval % 11 != $checksum) {
            return false;
        }

        return true;
    }
}
