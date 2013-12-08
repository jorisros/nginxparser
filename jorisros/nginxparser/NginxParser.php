<?php
/**
 * Created by PhpStorm.
 * User: jorisros
 * Date: 08/12/13
 * Time: 15:37
 */

namespace jorisros\nginxparser;

require_once 'NginxElement.php';


class NginxParser {

    /**
     * Contains the values
     * @var array
     */
    protected $arrValues = array();

    protected $identity = '';

    protected $regex = null;

    protected $parent = null;

    public function __construct($identity, $regex = null)
    {
        $this->identity = $identity;
        $this->regex = $regex;

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
     * @param $obj
     */
    protected function setParent($obj)
    {
        $this->parent = $obj;
    }

    /**
     *
     * @return string
     */
    public function build()
    {
        $first = null;

        if(is_object($this->parent))
        {
            $first = "\t";
        }

        $file = "\n".$first.$this->identity;

        if($this->regex)
        {
            $file .= " ".$this->regex;
        }
        $file .= " {\n";

        foreach($this->arrValues as $method=>$value)
        {
            switch($value)
            {
                case is_object($value):
                    $value->setParent($this);
                    $file .= $first."\t".$value."\n";
                break;
                case is_array($value):
                    $file .= $first."\t".$method."\t\t".implode(' ',$value).";\n";
                break;
                default:
                    $file .= $first."\t".$method."\t\t".$value.";\n";
                break;
            }
        }
        $file .= $first."}\n";


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

            return $this->arrValues[$method_name];
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

