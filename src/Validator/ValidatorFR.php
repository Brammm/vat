<?php

namespace Brammm\Vat\Validator;

/**
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10 C11]
 *
 * Range:
 *      C1 .. C2 Alphanumeric from A to Z or 0 to 9
 *      C2 .. C11 Numeric from 0 to 9
 *
 * Rules:
 * Case 1: Old Style
 *      [C1 C2] = ([C3 C4 C5 C6 C7 C8 C9 C10 C11] [1 2])modulo 97
 *
 * Case 2 : New Style
 *      S1 = Check Character (C1)
 *      S2 = Check Character (C2)
 *
 *      If C1 numeric then
 *          C2 alphabetic
 *          S = (S1 * 24) + (S2 – 10)
 *
 *      IF C1 alphabetic then
 *          S = (S1*34) + (S2-100)
 *
 *      P = (S/11) + 1
 *      R1 = (S)modulo11
 *      R2 = ( [C3 C4 C5 C6 C7 C8 C9 C10 C11] + P)modulo11
 *      R1 = R2
 *
 *      Check Character
 *      0-0, 1-1, 2-2, 3-3, 4-4, 5-5, 6-6, 7-7, 8-8, 9-9, 10-A, 11-B, 12-C, 13-D, 14-E, 15-F, 16-G, 17-H, 18-J, 19-K,
 *      20-L, 21-M, 22-N, 23-P, 24-Q, 25-R, 26-S, 27-T, 28-U, 29-V, 30-W, 31-X, 32-Y, 33-Z.
 *
 */
class ValidatorFR implements Validator
{
    use CountryCodeValidator;

    # the valid characters for the first two digits (O and I are missing)
    private const ALPHABET = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';

    /**
     * @inheritdoc
     */
    public function getCountryCode(): string
    {
        return 'FR';
    }

    /**
     * @param string $countryCode
     * @param string $vatNumber
     * @return bool
     */
    protected function isValid(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 11) {
            return false;
        }

        if (strpos(self::ALPHABET, $vatNumber[0]) === false) {
            return false;
        }

        if (strpos(self::ALPHABET, $vatNumber[1]) === false) {
            return false;
        }

        $checksum = substr($vatNumber, 0, 2);

        if (ctype_digit($checksum)) {
            $checkval = $this->validateOld($vatNumber);
        } else {
            $checkval = $this->validateNew($vatNumber);
        }

        if ($checksum != $checkval) {
            return false;
        }

        return true;
    }

    private function validateOld(string $vatNumber): string
    {
        $checkval = substr($vatNumber, 2);
        $checkval .= "12";
        $checkval = intval($checkval) % 97;

        return ($checkval == 0) ? "00" : $checkval;
    }

    private function validateNew(string $vatNumber): bool
    {
        $checkCharacter = array_flip(str_split(self::ALPHABET));

        if (ctype_digit($vatNumber[0])) {
            $checkval = ($checkCharacter[$vatNumber[0]] * 24) + $checkCharacter[$vatNumber[1]] - 10;
        } else {
            $checkval = ($checkCharacter[$vatNumber[0]] * 34) + $checkCharacter[$vatNumber[1]] - 100;
        }

        if (((intval(substr($vatNumber, 2)) + ($checkval / 11) + 1) % 11) != $checkval % 11) {
            return false;
        }

        return true;
    }
}
