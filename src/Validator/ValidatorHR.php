<?php

namespace Brammm\Vat\Validator;

/**
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9 C10 C11]
 */
class ValidatorHR implements VatNumberValidator
{
    /**
     * @param string $vatNumber
     * @return bool
     */
    public function validate($vatNumber): bool
    {
        if (strlen($vatNumber) != 11) {
            return false;
        }

        $product = 10;

        for ($i = 0; $i < 10; $i++) {
            $sum = ($vatNumber[$i] + $product) % 10;
            $sum = ($sum == 0) ? 10 : $sum;
            $product = (2 * $sum) % 11;
        }

        return (($product + (int)$vatNumber[10]) % 10 == 1) ? true : false;
    }
}
