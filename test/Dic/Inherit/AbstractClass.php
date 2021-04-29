<?php


namespace rollun\test\dic\Inherit;

class AbstractClass
{
    public $aTest;

    public function __construct()
    {
        $this->aTest = "test";
    }
}