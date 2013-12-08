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
     * Contains the values
     * @var array
     */
    protected $arrValues = array();


    public function __construct()
    {
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

        foreach($this->arrValues as $method=>$value)
        {
            if(is_array($value))
            {

            }else{
                $file .= "\t".$method."\t\t".$value.";\n";
            }

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

    public function __call($method, $value)
    {
        if(substr($method, 0, 3 ) === 'get')
        {

        }

        if(substr($method, 0, 3 ) === 'set')
        {
            $arrChar = array();
            for($i=3; $i<strlen($method); $i++)
            {
                if($i === 3)
                {
                    $method{$i} = strtolower($method{$i});
                }
                if(ctype_upper($method{$i}))
                {
                    $arrChar[] = '_';
                    $arrChar[] = strtolower($method{$i});
                }else{
                    $arrChar[] = $method{$i};
                }

            }
            $method_name = implode('',$arrChar);

            $this->arrValues[$method_name] = reset($value);
        }

        return $this;
    }

}

