<?php

namespace Brammm\Vat\Tests\Validator;

use Brammm\Vat\Exception\InvalidCountryCodeException;
use Brammm\Vat\Validator\Validator;

class InvalidCountryCodeValidator implements Validator
{
    /**
     * @inheritdoc
     */
    public function validate(string $countryCode, string $vatNumber): void
    {
        throw InvalidCountryCodeException::forCountryCode($countryCode);
    }

    /**
     * @inheritdoc
     */
    public function supportsCountry(string $countryCode): bool
    {
        return true;
    }
}
