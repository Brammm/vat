<?php

namespace Brammm\Vat\Validator;

use function Brammm\Vat\isEven;

/**
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10 C11]
 *
 * Range:
 *      C1 ... C11 Numeric from 0 to 9
 *      [C8 C9 C10] (>000 and <101) or (=120) or (=121) or (=999) or (=888)
 *
 * Rules:
 * C11
 *      S1 = C1 + C3 + C5 + C7 + C9
 *      S2 = D2 + D4 + D6 + D8 + D10
 *      where Di = int(Ci/5) + (2*Ci)modulo10
 *      C11 = (10 – (S1+S2)modulo10)modulo10
 */
class ValidatorIT implements Validator
{
    use CountryCodeValidator;

    /**
     * @inheritdoc
     */
    public function getCountryCode(): string
    {
        return 'IT';
    }

    /**
     * @inheritdoc
     */
    public function validate(string $countryCode, string $vatNumber): bool
    {
        if (strlen($vatNumber) != 11) {
            return false;
        }

        if (substr($vatNumber, 0, 7) == '0000000') {
            return false;
        }

        $checksum = (int)substr($vatNumber, -1);
        $Sum1 = $Sum2 = 0;
        for ($i = 1; $i <= 10; $i++) {
            if (!isEven($i)) {
                $Sum1 += $vatNumber[$i - 1];
            } else {
                $Sum2 += (int)($vatNumber[$i - 1] / 5) + ((2 * $vatNumber[$i - 1]) % 10);
            }
        }

        $checkval = (10 - ($Sum1 + $Sum2) % 10) % 10;

        if ($checksum != $checkval) {
            return false;
        }

        return true;
    }
}
