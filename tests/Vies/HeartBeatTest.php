<?php
declare (strict_types=1);

namespace DragonBe\Test\Vies;

use Brammm\Vat\HeartBeat;
use PHPUnit\Framework\TestCase;

class HeartBeatTest extends TestCase
{
    /**
     * @expectedException \DomainException
     * @covers \Brammm\Vat\HeartBeat::getHost
     */
    public function testExceptionThrownWhenNoHostIsConfigured()
    {
        $hb = new HeartBeat();
        $host = $hb->getHost();
        $this->assertNull($host);
    }

    /**
     * @covers \Brammm\Vat\HeartBeat::getPort
     */
    public function testDefaultPortIsHttp()
    {
        $hb = new HeartBeat();
        $port = $hb->getPort();
        $this->assertSame(80, $port);
    }

    /**
     * @covers Brammm\Vat\HeartBeat::setHost
     * @covers Brammm\Vat\HeartBeat::getHost
     */
    public function testCanSetHost()
    {
        $host = 'www.example.com';
        $hb = new HeartBeat();
        $hb->setHost($host);
        $this->assertSame($host, $hb->getHost());
    }

    /**
     * @covers Brammm\Vat\HeartBeat::setPort
     */
    public function testCanSetPort()
    {
        $port = 443;
        $hb = new HeartBeat();
        $hb->setPort($port);
        $this->assertSame($port, $hb->getPort());
    }

    /**
     * @covers Brammm\Vat\HeartBeat::__construct
     */
    public function testCanOverrideSettingsAtConstruct()
    {
        $host = 'www.example.com';
        $port = 8080;
        $hb = new HeartBeat($host, $port);
        $this->assertSame($host, $hb->getHost());
        $this->assertSame($port, $hb->getPort());
    }

    /**
     * @covers Brammm\Vat\HeartBeat::isAlive
     * @covers Brammm\Vat\HeartBeat::connect
     */
    public function testVerifyServicesIsAlive()
    {
        $host = '127.0.0.1';
        $port = -1;
        HeartBeat::$testingEnabled = true;
        HeartBeat::$testingServiceIsUp = true;
        $hb = new HeartBeat($host, $port);
        $result = $hb->isAlive();
        $this->assertTrue($result);
    }

    /**
     * @covers Brammm\Vat\HeartBeat::isAlive
     * @covers Brammm\Vat\HeartBeat::connect
     */
    public function testVerifyServicesIsDown()
    {
        $host = '127.0.0.1';
        $port = -1;
        HeartBeat::$testingEnabled = true;
        HeartBeat::$testingServiceIsUp = false;
        $hb = new HeartBeat($host, $port);
        $result = $hb->isAlive();
        $this->assertFalse($result);
    }
}
