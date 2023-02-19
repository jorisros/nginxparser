<?php

use JorisRos\NginxParser\NginxBuilder;
use JorisRos\NginxParser\NginxParser;
use PHPUnit\Framework\TestCase;

class NginxBuilderTest extends TestCase
{
    protected function setUp(): void
    {
        //$this->parser = new NginxParser();
    }

    protected function tearDown(): void
    {
        //unset($this->parser);
    }

    public function testSimple() {

        $location = new NginxParser('location','/');
        $location->setRoot('/usr/share/nginx/html')
            ->setIndex(array('index.html', 'index.htm'));
        $server = new NginxParser('server');
        $server->setListen(80)
            ->setServerName(array('localhost','local','serveralias'))
            ->setAccessLog('/var/log/nginx/log/host.access.log')
            ->setLocation($location);

        $builder = new NginxBuilder($server);
        $d = $builder->build();

        $this->assertInstanceOf(NginxBuilder::class, $builder);
    }
}