<?php

namespace jorisros\nginxparser;

class NginxLocation
{
    /**
     * @var string
     */
    protected $root_path = '';

    /**
     * @var array
     */
    protected $arr_index = array();

    protected $regex = '';

    public function __construct($regex)
    {
        $this->regex = $regex;
    }

    public function setRoot($path)
    {
        $this->root_path = $path;
        return $this;
    }

    public function setIndex($arr = array())
    {
        $this->arr_index = $arr;
        return $this;
    }

    public function build()
    {
        $str = "\n\tLocation ".$this->regex. " {\n";
        $str .= "\t\troot\t". $this->root_path.";\n";
        if(count($this->arr_index) >=1)
        {
            $str .= "\t\tindex\t".implode(' ', $this->arr_index).";\n";
        }
        $str .= "\t}\n";

        return $str;
    }

    public function __toString()
    {
        return $this->build();
    }
}


