<?php

namespace Brammm\Vat;

use Brammm\Vat\Validator\Validator;

class VatNumber
{
    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var string
     */
    private $vatNumber;

    private function __construct(string $countryCode, string $vatNumber)
    {
        $this->countryCode = $countryCode;
        $this->vatNumber = $vatNumber;
    }

    public static function fromIdentifier(string $identifier, Validator $validator)
    {
        $countryCode = substr($identifier, 0, 2);
        $vatNumber = substr($identifier, 2);

        $validator->validate($countryCode, $vatNumber);

        return new self(
            $countryCode,
            $vatNumber
        );
    }

    public static function fromCountryCodeAndVatNumber(string $countryCode, string $vatNumber, Validator $validator)
    {
        $validator->validate($countryCode, $vatNumber);

        return new self(
            $countryCode,
            $vatNumber
        );
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getVatNumber(): string
    {
        return $this->vatNumber;
    }
}
