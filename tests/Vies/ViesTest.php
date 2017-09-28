<?php
declare (strict_types=1);

namespace DragonBe\Test\Vies;

use Brammm\Vat\HeartBeat;
use Brammm\Vat\Tests\Validator\ValidValidator;
use Brammm\Vat\VatNumber;
use Brammm\Vat\Vies;
use PHPUnit\Framework\TestCase;

class ViestTest extends TestCase
{
    public function vatNumberProvider()
    {
        return  [
             ['0123456749','0123456749'],
             ['0123 456 749','0123456749'],
             ['0123.456.749','0123456749'],
             ['0123-456-749','0123456749'],
        ];
    }

    protected function createdStubbedViesClient($response)
    {
        $stub = $this->getMockFromWsdl(
            dirname(__FILE__) . '/_files/checkVatService.wsdl');
        $stub->expects($this->any())
             ->method('__soapCall')
             ->will($this->returnValue($response));

        $vies = new Vies();
        $vies->setSoapClient($stub);
        return $vies;
    }

    /**
     * @covers \Brammm\Vat\Vies::validate
     * @covers \Brammm\Vat\Vies::setSoapClient
     */
    public function testSuccessVatNumberValidation()
    {
        $response = new \StdClass();
        $response->countryCode = 'BE';
        $response->vatNumber = '0123.456.749';
        $response->requestDate = '1983-06-24+23:59';
        $response->valid = true;
        $response->traderName = '';
        $response->traderAddress = '';
        $response->requestIdentifier = 'XYZ1234567890';

        $vies = $this->createdStubbedViesClient($response);

        $response = $vies->validate($this->getVatNumber('BE', '0123.456.789'));
        $this->assertInstanceOf('\\Brammm\\Vat\\CheckVatResponse', $response);
        $this->assertTrue($response->isValid());
        return $response;
    }

    /**
     * @covers \Brammm\Vat\Vies::validate
     * @covers \Brammm\Vat\Vies::setSoapClient
     */
    public function testSuccessVatNumberValidationWithRequester()
    {
        $response = new \StdClass();
        $response->countryCode = 'BE';
        $response->vatNumber = '0123.456.749';
        $response->requestDate = '1983-06-24+23:59';
        $response->valid = true;
        $response->traderName = '';
        $response->traderAddress = '';
        $response->requestIdentifier = 'XYZ1234567890';

        $vies = $this->createdStubbedViesClient($response);

        $response = $vies->validate($this->getVatNumber('BE', '0123.456.789'));
        $this->assertInstanceOf('\\Brammm\\Vat\\CheckVatResponse', $response);
        $this->assertTrue($response->isValid());
        return $response;
    }

    /**
     * @covers \Brammm\Vat\Vies::validate
     * @covers \Brammm\Vat\Vies::setSoapClient
     */
    public function testFailureVatNumberValidation()
    {
        $response = new \StdClass();
        $response->countryCode = 'BE';
        $response->vatNumber = '0123.ABC.749';
        $response->requestDate = '1983-06-24+23:59';
        $response->valid = false;

        $vies = $this->createdStubbedViesClient($response);

        $response = $vies->validate($this->getVatNumber('BE', '0123.ABC.789'));
        $this->assertInstanceOf('\\Brammm\\Vat\\CheckVatResponse', $response);
        $this->assertFalse($response->isValid());
    }

    public function badCountryCodeProvider()
    {
        return [
            ['AA'],
            ['TK'],
            ['PH'],
            ['FS'],
        ];
    }

    /**
     * Test exception ViesServiceException is thrown after SoapFault exception
     *
     * @dataProvider vatNumberProvider
     * @expectedException \Brammm\Vat\ViesServiceException
     * @param $vat
     */
    public function testExceptionIsRaisedSoapFault($vat)
    {
        $soapFault = new \SoapFault("test", "myMessage");
        $stub = $this->getMockFromWsdl(
            dirname(__FILE__) . '/_files/checkVatService.wsdl');
        $stub->expects($this->any())
            ->method('__soapCall')
            ->will($this->throwException($soapFault));

        $vies = new Vies();
        $vies->setSoapClient($stub);

        $vies->validate($this->getVatNumber('BE', $vat));
    }

    /**
     * @param \Brammm\Vat\CheckVatResponse $response
     * @depends testSuccessVatNumberValidation
     * @covers \Brammm\Vat\CheckVatResponse::toArray
     */
    public function testConvertObjectIntoArray($response)
    {
        $array = $response->toArray();
        $this->assertSame('BE', $array['countryCode']);
        $this->assertSame('0123.456.749', $array['vatNumber']);
        $this->assertSame('1983-06-24', $array['requestDate']);
        $this->assertSame('XYZ1234567890', $array['identifier']);
        $this->assertTrue($array['valid']);
        $this->assertEmpty($array['name']);
        $this->assertEmpty($array['address']);
    }

    private function createHeartBeatMock($bool)
    {
        $heartBeatMock = $this->getMockBuilder(HeartBeat::class)
            ->setMethods(['isAlive'])
            ->getMock();

        $heartBeatMock->expects($this->once())
            ->method('isAlive')
            ->will($this->returnValue($bool));

        return $heartBeatMock;
    }

    /**
     * @covers \Brammm\Vat\Vies::getHeartBeat
     * @covers \Brammm\Vat\Vies::setHeartBeat
     */
    public function testServiceIsAlive()
    {
        $vies = new Vies();
        $hb = $this->createHeartBeatMock(true);
        $vies->setHeartBeat($hb);
        $this->assertTrue(
            $vies->getHeartBeat()->isAlive()
        );
    }

    /**
     * @covers \Brammm\Vat\Vies::getHeartBeat
     * @covers \Brammm\Vat\Vies::setHeartBeat
     */
    public function testServiceIsDown()
    {
        $vies = new Vies();
        $hb = $this->createHeartBeatMock(false);
        $vies->setHeartBeat($hb);
        $this->assertFalse(
            $vies->getHeartBeat()->isAlive()
        );
    }

    /**
     * @covers \Brammm\Vat\Vies::getSoapClient
     * @todo: SoapClient connects to european commission VIES service at initialisation
     */
    public function testGettingDefaultSoapClient()
    {
        if (defined('HHVM_VERSION') && ! extension_loaded('soap')) {
            $this->markTestSkipped('SOAP not installed');
        }
        $vies = new Vies();
        $vies->setSoapClient($this->createdStubbedViesClient('blabla')->getSoapClient());
        $soapClient = $vies->getSoapClient();
        $expected = '\\SoapClient';
        $this->assertInstanceOf($expected, $soapClient);
    }

    /**
     * @covers \Brammm\Vat\Vies::getSoapClient
     */
    public function testDefaultSoapClientIsLazyLoaded()
    {
        if (defined('HHVM_VERSION') && ! extension_loaded('soap')) {
            $this->markTestSkipped('SOAP not installed');
        }
        $wsdl = dirname(__FILE__) . '/_files/checkVatService.wsdl';
        $vies = new Vies();
        $vies->setWsdl($wsdl);
        $this->assertInstanceOf('\\SoapClient', $vies->getSoapClient());
    }

    /**
     * @covers \Brammm\Vat\Vies::setOptions
     * @covers \Brammm\Vat\Vies::getOptions
     */
    public function testOptionsCanBeSet()
    {
        $options = ['foo' => 'bar'];
        $vies = new Vies();
        $vies->setOptions($options);
        $this->assertSame($options, $vies->getOptions());
    }

    /**
     * @covers \Brammm\Vat\Vies::getWsdl
     */
    public function testGettingDefaultWsdl()
    {
        $vies = new Vies();
        $wsdl = $vies->getWsdl();
        $expected = sprintf(
            '%s://%s%s',
            Vies::VIES_PROTO,
            Vies::VIES_DOMAIN,
            Vies::VIES_WSDL
        );
        $this->assertSame($expected, $wsdl);
    }

    /**
     * @covers \Brammm\Vat\Vies::setWsdl
     */
    public function testSettingCustomWsdl()
    {
        $wsdl = 'http://www.example.com/?wsdl';
        $vies = new Vies();
        $vies->setWsdl($wsdl);
        $actual = $vies->getWsdl();
        $this->assertSame($wsdl, $actual);
    }

    /**
     * @covers \Brammm\Vat\Vies::setOptions
     * @covers \Brammm\Vat\Vies::getOptions
     */
    public function testSettingSoapOptions()
    {
        if (defined('HHVM_VERSION')) {
            $this->markTestSkipped('This test does not work for HipHop VM');
        }
        $options = [
            'soap_version' => SOAP_1_1,
        ];
        $vies = new Vies();
        $vies->setSoapClient($this->createdStubbedViesClient('blabla')->getSoapClient());
        $vies->setOptions($options);
        $soapClient = $vies->getSoapClient();
        $actual = $soapClient->_soap_version;
        $this->assertSame($options['soap_version'], $actual);
        $this->assertSame($options, $vies->getOptions());
    }

    /**
     * @covers \Brammm\Vat\Vies::getOptions
     */
    public function testDefaultOptionsAreEmpty()
    {
        $vies = new Vies();
        $options = $vies->getOptions();
        $this->assertInternalType('array', $options);
        $this->assertEmpty($options);
    }

    /**
     * @covers \Brammm\Vat\Vies::getHeartBeat
     */
    public function testGetDefaultHeartBeatWhenNoneSpecified()
    {
        $vies = new Vies();
        $hb = $vies->getHeartBeat();
        $this->assertInstanceOf('\\Brammm\\Vat\\HeartBeat', $hb);

        $this->assertSame('tcp://' . Vies::VIES_DOMAIN, $hb->getHost());
        $this->assertSame(80, $hb->getPort());
    }

    private function getVatNumber(string $countryCode, string $vatNumber)
    {
        return VatNumber::fromCountryCodeAndVatNumber($countryCode, $vatNumber, new ValidValidator());
    }
}
