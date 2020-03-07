<?php

namespace JorisRos\NginxParser;

class NginxElement
{
    /**
     * use
     *
     * @param $integer
     * @return int
     */
    public static function integer($integer)
    {
        return (int) $integer;
    }

    public static function hostname($hostname)
    {
        return $hostname;
    }

    /**
     *
     * @param string $string
     * @return string
     */
    public static function string($string = '')
    {
        return $string;
    }

}