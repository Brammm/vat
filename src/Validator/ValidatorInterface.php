<?php
/**
 * \Brammm\Vat
 *
 * @author  Paweł Krzaczkowski <krzaczek+github@gmail.com>
 * @license  MIT
 */

namespace Brammm\Vat\Validator;

interface ValidatorInterface
{
    /**
     * @param string $vatNumber
     * @return bool
     */
    public function validate($vatNumber);
}
