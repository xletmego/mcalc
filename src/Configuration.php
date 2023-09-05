<?php declare(strict_types=1);

namespace App;

class Configuration
{

    private $config;

    public function __construct(string $path)
    {
        $config = array();
        include($path);
        $this->config = $config;
    }

    public function get(string $key):string
    {
        $val = '';
        if (isset($this->config[$key])) {
            $val = $this->config[$key];
        }
        return strval($val);
    }
}
