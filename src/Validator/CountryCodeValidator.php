<?php

namespace Brammm\Vat\Validator;

trait CountryCodeValidator
{
    /**
     * Returns the two letter country code
     *
     * @return string
     */
    abstract public function getCountryCode(): string;

    public function validatesCountry(string $countryCode): bool
    {
        return $countryCode === $this->getCountryCode();
    }
}
