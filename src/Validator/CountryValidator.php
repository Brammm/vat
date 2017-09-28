<?php

namespace Brammm\Vat\Validator;

interface CountryValidator
{
    /**
     * @param string $vatNumber
     * @return bool
     */
    public function validate(string $vatNumber): bool;
}
