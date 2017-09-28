<?php

namespace Brammm\Vat\Validator;

/**
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9]
 *
 * Range:
 *      C1 ... C8 Numeric from 0 to 9
 *      C9 Alphabetic
 *      C1 0, 1, 3, 4, 5, 9
 *
 * Rules:
 * C1 C2
 *      C1C2 cannot be 12 (e.g. 12000139V is invalid)
 */
class ValidatorCY implements CountryValidator
{
    private const ALLOWED_C1 = [0, 1, 3, 4, 5, 9];

    /**
     * @inheritdoc
     */
    public function getCountryCode(): string
    {
        return 'CY';
    }

    /**
     * @inheritdoc
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }

        if (intval(substr($vatNumber, 0, 2) == 12)) {
            return false;
        }

        if (!in_array($vatNumber[0], self::ALLOWED_C1)) {
            return false;
        }

        if (!ctype_alpha($vatNumber[8])) {
            return false;
        }

        return true;
    }
}
