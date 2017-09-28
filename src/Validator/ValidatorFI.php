<?php

namespace Brammm\Vat\Validator;

use function Brammm\Vat\sumWeights;

/**
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8]
 *
 * Range:
 *      C2 .. C8 Numeric from 0 to 9
 *
 * Rules:
 * C8
 *      R = 11 - (7*C1 + 9*C2 + 10*C3 + 5*C4 + 8*C5 + 4*C6 + 2*C7) modulo11
 *      If R = 10 then, VAT number is invalid
 *      If R = 11 then C8 = 0
 *      Else C8 = R
 */
class ValidatorFI implements Validator
{
    use CountryCodeValidator;

    /**
     * @inheritdoc
     */
    public function getCountryCode(): string
    {
        return 'FI';
    }

    /**
     * @inheritdoc
     */
    public function validate(string $countryCode, string $vatNumber): bool
    {
        if (strlen($vatNumber) != 8) {
            return false;
        }

        $checksum = (int)$vatNumber[7];
        $weights = [7, 9, 10, 5, 8, 4, 2];
        $checkval = sumWeights($weights, $vatNumber);

        if (11 - ($checkval % 11) != $checksum) {
            return false;
        }

        return true;
    }
}
