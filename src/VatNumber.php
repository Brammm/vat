<?php

namespace Brammm\Vat;

use Brammm\Vat\Validator\AggregateValidator;

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

    public static function fromIdentifier(string $identifier, AggregateValidator $validator)
    {
        $countryCode = substr($identifier, 0, 2);
        $vatNumber = substr($identifier, 2);

        $vatNumber = self::filterVat($vatNumber);

        $validator->validate($countryCode, $vatNumber);

        return new self(
            $countryCode,
            $vatNumber
        );
    }

    public static function fromCountryCodeAndVatNumber(string $countryCode, string $vatNumber, AggregateValidator $validator)
    {
        $vatNumber = self::filterVat($vatNumber);

        $validator->validate($countryCode, $vatNumber);

        return new self(
            $countryCode,
            $vatNumber
        );
    }

    private static function filterVat(string $vatNumber)
    {
        return str_replace([' ', '.', '-'], '', $vatNumber);
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getVatNumber(): string
    {
        return $this->vatNumber;
    }

    public function __toString()
    {
        return $this->countryCode . $this->vatNumber;
    }
}
