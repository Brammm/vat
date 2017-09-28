<?php

namespace Brammm\Vat\Tests\Validator;

use Brammm\Vat\Exception\InvalidVatNumberException;
use Brammm\Vat\Validator\Validator;
use Brammm\Vat\Validator\ValidatorFactory;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /**
     * @var Validator
     */
    private $validator;

    public function setUp()
    {
        $this->validator = ValidatorFactory::create();
    }

    /**
     * @dataProvider validVatNumberProvider
     */
    public function testVatNumberChecksumSuccess($countryCode, $validNumbers)
    {
        if (! is_array($validNumbers)) {
            $validNumbers = [$validNumbers];
        }

        foreach ($validNumbers as $number) {
            $this->validator->validate($countryCode, $number);
            $this->assertTrue(true);
        }
    }

    /**
     * @dataProvider invalidVatNumberProvider
     */
    public function testVatNumberChecksumFailure($countryCode, $invalidNumbers)
    {
        foreach ($invalidNumbers as $number) {
            $this->expectException(InvalidVatNumberException::class);
            $this->validator->validate($countryCode, $number);
        }
    }

    public function validVatNumberProvider()
    {
        return [
            ['AT', 'U10223006'],
            ['BE', '776091951'],
            ['BG', ['204514061', '301004503']],
            ['CY', '00532445O'],
            ['CZ', ['46505334', '7103192745', '640903926', '395601439', '630903928']],
            ['DE', '111111125'],
            ['DK', '88146328'],
            ['EE', '100207415'],
            ['EL', '040127797'],
            ['ES', ['A0011012B', 'A78304516']],
            ['FI', '09853608'],
            ['FR', ['00300076965', 'K7399859412', '4Z123456782']],
            ['GB', ['434031494', 'GD001', 'HA500']],
            ['HR', '38192148118'],
            ['HU', ['21376414', '10597190']],
            ['IE', ['8Z49289F', '3628739L', '5343381W', '6433435OA']],
            ['IT', '00000010215'],
            ['LT', ['210061371310', '213179412', '290061371314', '208640716']],
            ['LU', '10000356'],
            ['LV', '40003009497'],
            ['MT', '15121333'],
            ['NL', '010000446B01'],
            ['PL', '5260001246'],
            ['PT', '502757191'],
            ['RO', ['11198699', '14186770']],
            ['SE', '556188840401'],
            ['SI', '15012557'],
            ['SK', '4030000007']
        ];
    }

    public function invalidVatNumberProvider()
    {
        return [
            ['AT', ['U1022300', 'A10223006', 'U10223005']],
            ['BE', ['0776091952', '07760919']],
            ['BG', ['10100450', '301004502']],
            ['CY', ['005324451', '0053244511', '12000139V', '72000139V']],
            ['CZ', ['4650533', '96505334', '46505333', '7103192743', '1903192745', '7133192745', '395632439', '396301439', '545601439', '640903927', '7103322745']],
            ['DE', ['111111124', '1234567']],
            ['DK', ['88146327', '1234567']],
            ['EE', ['1002074', 'A12345678']],
            ['EL', ['040127796', '1234567']],
            ['ES', ['K0011012B', '12345678', 'K001A012B', 'A0011012C']],
            ['FI', ['09853607', '1234567']],
            ['FR', ['0030007696A', '1234567890', 'K6399859412', 'KO399859412', 'IO399859412']],
            ['GB', ['434031493', '12345', 'GD500', 'HA100', '12345678']],
            ['HR', ['3819214811', '1234567890A']],
            ['HU', ['2137641', '1234567A']],
            ['IE', ['8Z49389F', '1234567', '6433435OB']],
            ['IT', ['00000010214', '1234567890', '00000001234']],
            ['LT', ['213179422', '21317941', '1234567890', '1234567890AB']],
            ['LU', ['10000355', '1234567']],
            ['LV', ['40013009497', '40003009496', '1234567890', '00212345678']],
            ['MT', ['15121332', '1234567', '05121333']],
            ['NL', ['010000436B01', '12345678901', '123456789A12', '123456789B00']],
            ['PL', ['12342678090', '1212121212']],
            ['PT', ['502757192', '12345678']],
            ['RO', ['11198698', '1', '12345678902']],
            ['SE', ['556188840400', '1234567890', '556181140401']],
            ['SI', ['15012556', '12345670', '01234567', '1234567']],
            ['SK', ['4030000006', '123456789', '0123456789', '4060000007']]
        ];
    }
}
