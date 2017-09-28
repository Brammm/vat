<?php

namespace Brammm\Vat\Validator;

/**
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8]
 */
class ValidatorIE implements CountryValidator
{
    private const ALPHATBET = 'WABCDEFGHIJKLMNOPQRSTUV';

    /**
     * @inheritdoc
     */
    public function validate(string $vatNumber): bool
    {
        if (strlen($vatNumber) != 8 && strlen($vatNumber) != 9) {
            return false;
        }

        if (!$this->validateIENew($vatNumber) && !$this->validateIEOld($vatNumber)) {
            return false;
        }

        return true;
    }

    private function validateIEOld(string $vatNumber): bool
    {
        $transform = ['0', substr($vatNumber, 2, 5), $vatNumber[0], $vatNumber[7]];
        $vat_id = join('', $transform);

        return $this->validateIENew($vat_id);
    }

    private function validateIENew(string $vatNumber): bool
    {
        $checksum = strtoupper(substr($vatNumber, 7, 1));
        $checkNumber = substr($vatNumber, 0, 8);
        $checkval = 0;
        $checkchar = 'A';

        for ($i = 2; $i <= 8; $i++) {
            $checkval += (int)$checkNumber[8 - $i] * $i;
        }

        if (strlen($vatNumber) == 9) {
            $checkval += (9 * strpos(self::ALPHATBET, $vatNumber[8]));
        }

        $checkval = ($checkval % 23);

        if ($checkval == 0) {
            $checkchar = 'W';
        } else {
            for ($i = $checkval - 1; $i > 0; $i--) {
                $checkchar++;
            }
        }
        if ($checkchar != $checksum) {
            return false;
        }

        return true;
    }
}
