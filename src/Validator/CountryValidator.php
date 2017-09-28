<?php

namespace Brammm\Vat\Validator;

interface CountryValidator
{
    /**
     * @param string $countryCode
     * @param string $vatNumber
     * @return bool
     */
    public function validate(string $countryCode, string $vatNumber): bool;

    /**
     * Returns the two character country code
     *
     * @return string
     */
    public function getCountryCode(): string;
}
