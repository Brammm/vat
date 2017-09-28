<?php

namespace Brammm\Vat\Validator;

interface VatNumberValidator
{
    /**
     * @param string $vatNumber
     * @return bool
     */
    public function validate(string $vatNumber): bool;
}
