nginxparser
===========

Create Nginx config files from php

Example to use

```php
<?php

use jorisros\nginxparser\NginxParser;
use jorisros\nginxparser\NginxElement;
use jorisros\nginxparser\NginxLocation;

require_once 'jorisros/nginxparser/NginxParser.php';

$config = new NginxParser();

$location = new NginxLocation('/');
$location->setRoot('/usr/share/nginx/html')
         ->setIndex(array('index.html', 'index.htm'));

$config ->setPort(80)
        ->setServerName('localhost')
        ->setServerAlias(array('local','serveralias'))
        ->setAccessLog('/var/log/nginx/log/host.access.log')
        ->setLocation($location);

print($config);


if($config->validate())
{
    $strFile = $config->build();
    file_put_contents('server.conf', $strFile);
}else{
    foreach ($config->getValidatorErrors() as $error) {
        # code...
    }
}```