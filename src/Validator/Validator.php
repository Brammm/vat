<?php

namespace Brammm\Vat\Validator;

use Brammm\Vat\Exception\InvalidVatNumberException;

interface Validator
{
    /**
     * @param string $countryCode
     * @param string $vatNumber
     *
     * @throws InvalidVatNumberException
     *
     * @return void
     */
    public function validate(string $countryCode, string $vatNumber): void;

    /**
     * Returns boolean wether or not the validator works for the given country code
     *
     * @param string $countryCode
     *
     * @return bool
     */
    public function supportsCountry(string $countryCode): bool;
}
