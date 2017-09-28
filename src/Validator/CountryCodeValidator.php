<?php

namespace Brammm\Vat\Validator;

use Brammm\Vat\Exception\InvalidVatNumberException;

trait CountryCodeValidator
{
    /**
     * Returns the two letter country code
     *
     * @return string
     */
    abstract public function getCountryCode(): string;

    /**
     * @param string $vatNumber
     *
     * @return bool
     */
    abstract protected function isValid(string $vatNumber): bool;

    public function supportsCountry(string $countryCode): bool
    {
        return $countryCode === $this->getCountryCode();
    }

    /**
     * @see Validator::validate()
     *
     * @param string $countryCode
     * @param string $vatNumber
     *
     * @throws InvalidVatNumberException
     */
    public function validate(string $countryCode, string $vatNumber): void
    {
        if (!$this->isValid($vatNumber)) {
            throw InvalidVatNumberException::forVatNumber($vatNumber);
        }
    }
}
