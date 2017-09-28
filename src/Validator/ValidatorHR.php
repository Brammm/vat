<?php
/**
 * \Brammm\Vat
 *
 * @author  Paweł Krzaczkowski <krzaczek+github@gmail.com>
 * @license  MIT
 */

namespace Brammm\Vat\Validator;

/**
 * Class ValidatorHR
 * @package Brammm\Vat\Validator
 */
class ValidatorHR implements VatNumberValidator
{
    /**
     * @param string $vatNumber
     * @return bool
     */
    public function validate($vatNumber)
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
