[![Build Status](https://travis-ci.org/jorisros/nginxparser.png?branch=master)](https://travis-ci.org/jorisros/nginxparser)
NginxParser
===========

Read and create Nginx config files in php
Requirements
------------
* PHP >= 7.2
* Nginx installed (for the validate function)

Composer
--------
Use composer to to add the classes to your project
```bash
composer require jorisros/nginxparser
```

Run tests
--------
Run in the main directory the following command
```bash
./vendor/bin/phpunit tests
```

Examples
--------
Examples to use the class

Simple config file

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use JorisRos\NginxParser\NginxParser;
use JorisRos\NginxParser\NginxElement;

$config = new NginxParser('server');

$location = new NginxParser('location','/');
$location->setRoot('/usr/share/nginx/html')
         ->setIndex(array('index.html', 'index.htm'));

$config ->setPort(80)
        ->setServerName(array('localhost','local','serveralias'))
        ->setAccessLog('/var/log/nginx/log/host.access.log')
        ->setLocation($location);

if($config->validate())
{
    $strFile = $config->build();
    file_put_contents('server.conf', $strFile);
}else{
    foreach ($config->getValidatorErrors() as $error) {
        # code...
    }
}
```
It will result in
```
server {
	port		80;
	server_name		localhost;
	server_alias		local serveralias;
	access_log		/var/log/nginx/log/host.access.log;

	location / {
		root		/usr/share/nginx/html;
		index		index.html index.htm;
	}

}
```
Read existing config file
```
<?php

require __DIR__ . '/vendor/autoload.php';

use JorisRos\NginxParser\NginxParser;
use JorisRos\NginxParser\NginxElement;

$d = new NginxParser();
$objects = $d->readFromFile('Resources/nginx-config/nginx.conf');

//var_dump($objects);

foreach($objects as $object)
{
    print($object->build());
}

```
[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/jorisros/nginxparser/trend.png)](https://bitdeli.com/free "Bitdeli Badge")
