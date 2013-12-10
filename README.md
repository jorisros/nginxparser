NginxParser
===========

Read and create Nginx config files in php
Requirements
------------
* PHP >= 5.3
* Nginx installed (for the validate function)

Examples
--------
Examples to use the class

Simple config file

```php
<?php

use jorisros\nginxparser\NginxParser;
use jorisros\nginxparser\NginxElement;

require_once 'jorisros/nginxparser/NginxParser.php';

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

use jorisros\nginxparser\NginxParser;
use jorisros\nginxparser\NginxElement;

require_once 'jorisros/nginxparser/NginxParser.php';

$d = new NginxParser();
$objects = $d->readFromFile('Resources/nginx-config/nginx.conf');

//var_dump($objects);

foreach($objects as $object)
{
    print($object->build());
}

```