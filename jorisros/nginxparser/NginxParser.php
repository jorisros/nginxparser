<?php
/**
 * Created by PhpStorm.
 * User: jorisros
 * Date: 08/12/13
 * Time: 15:37
 */

namespace jorisros\nginxparser;

require_once 'NginxLocation.php';
require_once 'NginxElement.php';


class NginxParser {

    /**
     * Default value for the port
     * @var int
     */
    protected $port = 80;

    /**
     * @var string
     */
    protected $server_name = '';

    /**
     * @var NginxLocation
     */
    protected $location = null;


    public function __construct()
    {
        return $this;
    }

    /**
     *
     * @param NginxElement $integer
     * @return $this
     */
    public function setPort($integer)
    {
        $this->port = $integer;
        return $this;
    }

    /**
     *
     * @param NginxElement $hostname
     * @return $this
     */
    public function setServerName($hostname)
    {
        $this->server_name = $hostname;
        return $this;
    }

    public function setServerAlias($array = array())
    {
        return $this;
    }

    public function setAccessLog($file)
    {
        return $this;
    }

    public function setLocation(NginxLocation $location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        return false;
    }

    /**
     *
     * @return string
     */
    public function build()
    {
        $file = "\nserver {\n";
        $file .= "\tlisten\t\t".$this->port.";\n";
        $file .= "\tserver_name\t".$this->server_name.";\n";
        if($this->location)
        {
            $file .= $this->location;
        }
        $file .= "}\n";


        return $file;
    }

    /**
     *
     * @return array
     */
    public function getValidatorErrors()
    {
        return array();
    }

    public function __toString()
    {
        return $this->build();
    }

}

