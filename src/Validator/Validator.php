<?php

namespace Brammm\Vat\Validator;

use Brammm\Vat\Exception\InvalidCountryCodeException;
use Brammm\Vat\Exception\InvalidVatNumberException;

class Validator
{
    /**
     * @var CountryValidator[]
     */
    private $validators;

    /**
     * @param CountryValidator[] $validators
     */
    public function __construct(array $validators)
    {
        foreach ($validators as $validator) {
            $this->addValidator($validator);
        }
    }

    public function addValidator(CountryValidator $validator): void
    {
        $this->validators[$validator->getCountryCode()] = $validator;
    }

    /**
     * Validates a country code and VAT number combination
     *
     * @param string $countryCode
     * @param string $vatNumber
     *
     * @throws InvalidCountryCodeException
     * @throws InvalidVatNumberException
     */
    public function validate(string $countryCode, string $vatNumber): void
    {
        if (!isset($this->validators[$countryCode])) {
            throw InvalidCountryCodeException::forCountryCode($countryCode);
        }

        if (!$this->validators[$countryCode]->validate($vatNumber)) {
            throw InvalidVatNumberException::forVatNumber($vatNumber);
        }
    }
}
