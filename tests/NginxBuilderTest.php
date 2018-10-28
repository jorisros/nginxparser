<?php


namespace jorisros\nginxparser;

use jorisros\nginxparse;

require_once "jorisros/nginxparser/NginxParser.php";
require_once "jorisros/nginxparser/NginxBuilder.php";

class NginxBuilderTest extends  \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        //$this->parser = new NginxParser();
    }

    protected function tearDown()
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