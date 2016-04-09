<?php

class Options
{

    private $optionsArray;

    public function __construct()
    {
        $this->optionsArray = parse_ini_file(CONFIG_DIR . 'options.ini', false);
    }


    public function get($str_Key)
    {
        return $this->optionsArray[$str_Key];
    }


    public function set($str_Key, $str_Value)
    {
        // TODO: add 'set' method to options
    }

}
