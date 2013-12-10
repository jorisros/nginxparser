<?php

use jorisros\nginxparser\NginxParser;

require_once "jorisros/nginxparser/NginxParser.php";

class NginxparserTest extends PHPUnit_Framework_TestCase
{
    /** @var NginxParser */
    protected $parser;

    protected function setUp()
    {
        //$this->parser = new NginxParser();
    }

    protected function tearDown()
    {
        //unset($this->parser);
    }

    public function testSimple() {
        $expected = <<<EOF

http {
}

EOF;

        $this->parser = new NginxParser('http');
        $actual = $this->parser->build();

        $this->assertEquals($expected, $actual);
    }
}