<?php

namespace JorisRos\NginxParser;

class NginxParser {

    /** @var array Contains the values of the section */
    protected $arrValues = array();

    /** @var null|string name of the section */
    protected $identity = '';

    /** @var null regular expression of the section */
    protected $regex = null;

    /** @var null The depth of a object */
    protected $parent = null;

    /**
     * Constructor of the parser
     *
     * @param null $identity
     * @param null $regex
     */
    public function __construct($identity = null, $regex = null)
    {
        $this->identity = $identity;
        $this->regex = $regex;

        return $this;
    }

    /**
     * Read a file and returns the config objects
     *
     * @param $path
     * @return array
     */
    public function readFromFile($path)
    {
        $file = file_get_contents($path);
        //preg_match_all("/{.*?}/",$file,$matches);
        $lines = explode("\n",$file);
        $space = false;
        $begin_method = false;
        $end_method = false;

        $objects = array();
        $objectCounter = 0;
$l = 0;
        foreach($lines as $line)
        {
            $str = '';
            $words = array();

            $lastChar = 0;
            for($i=0; $i<strlen($line); $i++)
            {
                //var_dump($line{$i});
                switch($line{$i})
                {
                    case ' ':
                        //$word[$l] = strpos($line, $lastChar, $i);
                        $lastChar = $i;
                        $space = true;
                    break;
                    case '{':
                        $begin_method = true;
                    break;
                    case '}':
                        $end_method = true;
                    break;
                    case '#':
                        $i = strlen($line);
                    case ';':
                    break;
                    default:
                        $str .= $line{$i};
                    break;
                }
                if($space)
                {
                    if(strlen($str) >0 )
                    {
                    $words[] = $str;
                       // var_dump($str);
                    $str = '';
                    }
                    $space = false;
                }
                if($begin_method)
                {
                    $objects[$objectCounter] = new NginxParser($words[0]);
                    $words = array();
                    //var_dump($words);
                    $begin_method = false;
                }

                if($end_method)
                {
                    $objectCounter++;
                    $end_method = false;
                }
            }
            if(isset($objects[$objectCounter]) && is_object($objects[$objectCounter]))
            {
                $command = '';
               // var_dump('line');
                if(count($words)>=1)
                {
                    $command = reset($words);
                    //var_dump($line);
                    $endPosWord = strpos($line, $command)+strlen($command);
                    $value = substr($line, $endPosWord, strlen($line));
                    $arrValue = explode(' ', str_replace(';','',$value));
                    $returnArray = array();
                    foreach($arrValue as $strVal)
                    {
                        if($strVal != '')
                        {
                            $returnArray[] = $strVal;
                        }
                    }

                    $arrChar = array();
                    $upper = false;
                    $method = $command;
                    for($i=0; $i<strlen($method); $i++)
                    {
                        if($i === 0)
                        {
                            $method{$i} = strtoupper($method{$i});
                        }
                        if($method{$i} === '_')
                        {
                           // $i++;
                            $upper = true;
                            //$arrChar[] = strtoupper($method{$i});
                        }else{
                            if($upper)
                            {
                                $arrChar[] = strtoupper($method{$i});
                                $upper = false;
                            }else{
                                $arrChar[] = $method{$i};
                            }
                        }

                    }

                    $method_name = 'set'.implode('',$arrChar);
                    //var_dump($method_name);


                    if(count($returnArray) ==1)
                    {
                        $objects[$objectCounter]->$method_name(reset($returnArray));
                    }
                    if(count($returnArray) >=2)
                    {
                        $objects[$objectCounter]->$method_name($returnArray);

                    }
                }

            }


            $l++;
        }

        return $objects;
        //print($file);
    }

    /**
     * Check if the config file is correct
     *
     * @return bool
     */
    public function validate()
    {
        //@TODO build validation functionality
        return false;
    }

    /**
     * Set a parent object
     *
     * @param $obj
     */
    protected function setParent($obj)
    {
        $this->parent = $obj;
    }

    /**
     * Parse the object to a string
     *
     * @return string
     */
    public function build()
    {
        $f = new NginxBuilder($this);

        return $f->build();
    }

    public function getParent()
    {
        return $this->parent;
    }


    public function getIdentity()
    {
        return $this->identity;
    }

    public function getValues()
    {
        return $this->arrValues;
    }

    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * Returns the errors of the validate
     *
     * @return array
     */
    public function getValidatorErrors()
    {
        //@TODO build the error functionality
        return array();
    }

    /**
     * Returns the parsed object
     *
     * @return string
     */
    public function __toString()
    {
        return $this->build();
    }

    /**
     * Override magic __call function
     *
     * @param $header
     * @param $variable
     * @return array
     */
    public function setProxySetHeader($header, $variable)
    {
        return $this->arrValues['proxy_set_header'][] = array($header,$variable);
    }

    /**
     * Magic function for dynamicly generating getters and setters
     *
     * @param $method
     * @param $value
     * @return $this
     */
    public function __call($method, $value)
    {
        if(substr($method, 0, 3 ) === 'get')
        {
            $method_name = $this->convertMethod($method);

            return $this->arrValues[$method_name];
        }

        if(substr($method, 0, 3 ) === 'set')
        {
            $method_name = $this->convertMethod($method);

            $value = reset($value);

            if (is_object($value)) {
                $this->parent = $value;
            }
            $this->arrValues[$method_name] = $value;
        }

        return $this;
    }

    /**
     * Loops through the defined methods
     *
     * @param $method
     * @return string
     */
    private function convertMethod($method): string
    {
        $arrChar = [];

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

        return implode('', $arrChar);
    }

}

