<?php

namespace Brammm\Vat\Tests;

use Brammm\Vat\Exception\InvalidVatNumberException;
use Brammm\Vat\Tests\Validator\InvalidVatNumberValidator;
use Brammm\Vat\Tests\Validator\ValidValidator;
use Brammm\Vat\VatNumber;
use PHPUnit\Framework\TestCase;

class VatNumberTest extends TestCase
{
    public function testItConstructsFromIdentifier()
    {
        $vatNumber = VatNumber::fromIdentifier('BE0123456789', new ValidValidator());

        $this->assertSame('BE', $vatNumber->getCountryCode());
        $this->assertSame('0123456789', $vatNumber->getVatNumber());
    }

    public function testItConstructsFromCountryCodeAndVatNumber()
    {
        $vatNumber = VatNumber::fromCountryCodeAndVatNumber('BE', '0123456789', new ValidValidator());

        $this->assertSame('BE', $vatNumber->getCountryCode());
        $this->assertSame('0123456789', $vatNumber->getVatNumber());
    }

    public function testItFiltersVatNumber()
    {
        $vatNumber = VatNumber::fromIdentifier('BE0123.456-789', new ValidValidator());

        $this->assertSame('0123456789', $vatNumber->getVatNumber());
    }

    public function testItDoesntCatchValidatorException()
    {
        $this->expectException(InvalidVatNumberException::class);
        VatNumber::fromIdentifier('BE0123456789', new InvalidVatNumberValidator());
    }
}
