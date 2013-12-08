nginxparser
===========

Create Nginx config files from php

Example
-------
Example to use the class as simple config file

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
        ->setServerName('localhost')
        ->setServerAlias(array('local','serveralias'))
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