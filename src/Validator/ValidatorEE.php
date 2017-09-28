<?php
/**
 * \Brammm\Vat
 *
 * @author  Paweł Krzaczkowski <krzaczek+github@gmail.com>
 * @license  MIT
 */

namespace Brammm\Vat\Validator;
use function Brammm\Vat\sumWeights;

/**
 * Class ValidatorEE
 * @package Brammm\Vat\Validator
 *
 * VAT format: [C1 C2 C3 C4 C5 C6 C7 C8 C9]
 *
 * Range:
 *      C1 .. C9 Numeric
 *      C1C2 = 10
 *
 * Rules:
 * C9
 *      A1 = 3*1 + 7*0 + 1*0 + 3*2 + 7*0 + 1*7 + 3*4 + 7*1 = 35
 *      A2 = CEIL(35;10) = 40
 *      C9 = 40 - 35 = 5
 */
class ValidatorEE implements VatNumberValidator
{
    /**
     * @param string $vatNumber
     * @return bool
     */
    public function validate($vatNumber)
    {
        if (strlen($vatNumber) != 9) {
            return false;
        }

        $weights = [3, 7, 1, 3, 7, 1, 3, 7];
        $checksum = (int)$vatNumber[8];
        $checkval = sumWeights($weights, $vatNumber);

        $checkval = (ceil($checkval / 10) * 10) - $checkval;

        if ($checkval != $checksum) {
            return false;
        }

        return true;
    }
}
