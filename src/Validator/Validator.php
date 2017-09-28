<?php

namespace Brammm\Vat\Validator;

interface Validator
{
    /**
     * @param string $countryCode
     * @param string $vatNumber
     * @return bool
     */
    public function validate(string $countryCode, string $vatNumber): bool;

    /**
     * Returns wether or not the validator works for the given country code
     *
     * @return bool
     */
    public function validatesCountry(string $countryCode): bool;
}
