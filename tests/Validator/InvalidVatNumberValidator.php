<?php

namespace Brammm\Vat\Tests\Validator;

use Brammm\Vat\Exception\InvalidCountryCodeException;
use Brammm\Vat\Exception\InvalidVatNumberException;
use Brammm\Vat\Validator\Validator;

class InvalidVatNumberValidator implements Validator
{
    /**
     * @inheritdoc
     */
    public function validate(string $countryCode, string $vatNumber): void
    {
        throw InvalidVatNumberException::forVatNumber($vatNumber);
    }

    /**
     * @inheritdoc
     */
    public function supportsCountry(string $countryCode): bool
    {
        return true;
    }
}
