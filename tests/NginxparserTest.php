<?php

namespace jorisros\nginxparser\tests;


use JorisRos\NginxParser\NginxBuilder;
use JorisRos\NginxParser\NginxParser;

class NginxparserTest extends \PHPUnit\Framework\TestCase
{
    /** @var NginxParser */
    protected $parser;
    
    public function testSimple() {
        $expected = <<<EOF

http {
}

EOF;

        $this->parser = new NginxParser('http');
        $actual = $this->parser->build();

        $this->assertEquals($expected, $actual);
    }

    public function testSub() {
        $expected = <<<EOF

server {
	listen		80;
	server_name		localhost local serveralias;
	access_log		/var/log/nginx/log/host.access.log;

	location / {
		root		/usr/share/nginx/html;
		index		index.html index.htm;
	}

}

EOF;

        $location = new NginxParser('location','/');
        $location->setRoot('/usr/share/nginx/html')
                 ->setIndex(array('index.html', 'index.htm'));
        $server = new NginxParser('server');
        $server->setListen(80)
            ->setServerName(array('localhost','local','serveralias'))
            ->setAccessLog('/var/log/nginx/log/host.access.log')
            ->setLocation($location);
        $actual = $server->build();

        $this->assertEquals($expected, $actual);
    }

    public function testReadfile() {
        $expected = <<<EOF

http {
	include		/etc/nginx/mime.types;
}

EOF;

        $location = new NginxParser();
        $objs = $location->readFromFile('./tests/nginx_test_comment.conf');

        //$actual = $location->build();
        $actual = reset($objs);

        $this->assertEquals($expected, $actual);
    }

    public function testReadfileComment() {
        $expected = <<<EOF

http {
	include		/etc/nginx/mime.types;
}

EOF;

        $location = new NginxParser();
        $objs = $location->readFromFile('./tests/nginx_test.conf');

        //$actual = $location->build();
        $actual = reset($objs);

        $this->assertEquals($expected, $actual);
    }
}
