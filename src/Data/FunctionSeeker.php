<?php

namespace App\Data;

use ReflectionClass;

class FunctionSeeker
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function isExists():bool
    {
        $reflection = new ReflectionClass(Functions::class);
        $classMethods = $reflection->getMethods();

        foreach ($classMethods as $classMethod){
            if($classMethod->name === $this->name){
                return true;
            }
        }
        return false;
    }

    public function getParams(): array
    {
        $params = [];

        $reflection = new ReflectionClass(Functions::class);
        $method = $reflection->getMethod($this->name);

        if(!$method instanceof \ReflectionMethod){
            return $params;
        }

        $variables = $method->getParameters();
        foreach ($variables as $variable){
            $params[$variable->name] = 0;
        }
        return $params;

    }

    public function getResult(array $params)
    {
        return call_user_func_array([Functions::class, $this->name], $params);
    }
}