<?php

namespace Brammm\Vat\Tests;

use Brammm\Vat\Exception\InvalidVatNumberException;
use Brammm\Vat\Validator\Validator;
use Brammm\Vat\VatNumber;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class VatNumberTest extends TestCase
{
    /**
     * @var Validator|PHPUnit_Framework_MockObject_MockObject
     */
    private $validator;

    public function setUp()
    {
        $this->validator = $validator = $this->createMock(Validator::class);
    }

    public function testItConstructsFromIdentifier()
    {
        $vatNumber = VatNumber::fromIdentifier('BE0123456789', $this->validator);

        $this->assertSame('BE', $vatNumber->getCountryCode());
        $this->assertSame('0123456789', $vatNumber->getVatNumber());
    }

    public function testItConstructsFromCountryCodeAndVatNumber()
    {
        $vatNumber = VatNumber::fromCountryCodeAndVatNumber('BE', '0123456789', $this->validator);

        $this->assertSame('BE', $vatNumber->getCountryCode());
        $this->assertSame('0123456789', $vatNumber->getVatNumber());
    }

    public function testItDoesntCatchValidatorException()
    {
        $this->validator->method('validate')
            ->willThrowException(new InvalidVatNumberException());

        $this->expectException(InvalidVatNumberException::class);
        VatNumber::fromIdentifier('BE0123456789', $this->validator);
    }
}
