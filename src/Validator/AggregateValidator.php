<?php

namespace Brammm\Vat\Validator;

use Brammm\Vat\Exception\InvalidCountryCodeException;

class AggregateValidator implements Validator
{
    /**
     * @var Validator[]
     */
    private $validators;

    /**
     * @param Validator[] $validators
     */
    public function __construct(Validator ...$validators)
    {
        $this->validators = $validators;
    }

    /**
     * @inheritdoc
     */
    public function validate(string $countryCode, string $vatNumber): void
    {
        $validator = $this->getValidator($countryCode);

        if (null === $validator) {
            throw InvalidCountryCodeException::forCountryCode($countryCode);
        }

        $this->getValidator($countryCode)->validate($countryCode, $vatNumber);
    }

    /**
     * @inheritdoc
     */
    public function supportsCountry(string $countryCode): bool
    {
        return (bool)$this->getValidator($countryCode);
    }

    /**
     * @param string $countryCode
     *
     * @throws InvalidCountryCodeException
     *
     * @return Validator|null
     */
    private function getValidator(string $countryCode): ?Validator
    {
        foreach ($this->validators as $validator) {
            if ($validator->supportsCountry($countryCode)) {
                return $validator;
            }
        }

        return null;
    }
}
