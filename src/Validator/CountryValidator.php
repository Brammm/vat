<?php

namespace Brammm\Vat\Validator;

interface CountryValidator
{
    /**
     * @param string $vatNumber
     *
     * @return bool
     */
    public function validate(string $vatNumber): bool;

    /**
     * Returns the two character country code
     *
     * @return string
     */
    public function getCountryCode(): string;
}
