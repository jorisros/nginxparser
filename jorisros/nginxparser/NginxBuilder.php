<?php

namespace jorisros\nginxparser;


class NginxBuilder
{
    /**
     * @var NginxParser
     */
    private $parser;

    /**
     * @var string
     */
    private $indent = '';

    private $content = '';

    public function __construct(NginxParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Build the Nginx file
     *
     * @return string
     */
    public function build()
    {
        $this->setIndent();

        $this->addContent($this->setIdentity());

        $this->addContent($this->setRegex());

        $this->addContent(" {\n");

        $this->addContent($this->parseValues());

        $this->addContent($this->indent . "}\n");

        return $this->content;
    }

    /**
     * Choose the type and converts the lines to a string
     *
     * @return string
     */
    private function parseValues(): string
    {
        $lines = [];

        foreach ($this->parser->getValues() as $method => $value) {
            $line = $this->chooseType($method, $value);

            if (is_array($line)) {
                $lines[md5(implode('',$line))] = implode('', $line);
            } else {
                $lines[md5($line)] = $line;
            }
        }

        return implode('', $lines);
    }

    /**
     * Choose the type
     *
     * @param $method
     * @param $value
     * @return string
     */
    private function chooseType($method, $value)
    {
        switch ($value) {
            case is_object($value):
                return $this->chooseTypeObject($value);
                break;
            case is_array($value):
                return $this->chooseTypeArray($method, $value);
                break;
            default:
                return sprintf($this->getTemplate(), $this->indent, $method, $value);
                break;
        }
    }

    /**
     * Converts a object to a string
     *
     * @param $value
     * @return string
     */
    private function chooseTypeObject($value): string
    {
        $builder = new NginxBuilder($value);
        $builder->setIndent("\t");

        return $this->indent . "\t" . $builder->build() . "\n";
    }

    /**
     * Converts the array to a string
     *
     * @param $method
     * @param $array
     * @return string
     */
    private function chooseTypeArray($method, $array)
    {
        $lines = [];

        foreach($array as $item){

            if(is_array($item)) {
                $line = sprintf($this->getTemplate(), $this->indent, $method, implode(' ',$item));
            } else {
                $line = sprintf($this->getTemplate(), $this->indent, $method, implode(' ',$array));
            }

            $lines[md5($line)] = $line;
        }

        return $lines;
    }

    /**
     * Returns the template for generating a string used in sprintf
     *
     * @return string
     */
    private function getTemplate(): string
    {
        return "%s\t%s\t\t%s;\n";
    }

    /**
     * Add the content
     *
     * @param $content
     */
    private function addContent($content): void
    {
        $this->content .= $content;
    }

    /**
     * Sets the ident when it is a parent
     */
    public function setIndent($value = ""): void
    {
        $this->indent = $this->indent  . $value;
    }

    /**
     * Returns the identity of the file
     *
     * @return string
     */
    private function setIdentity(): string
    {
        return "\n" . $this->indent . $this->parser->getIdentity();
    }

    private function setRegex()
    {
        if ($this->parser->getRegex()){
            return " " . $this->parser->getRegex();
        }

        return "";
    }
}