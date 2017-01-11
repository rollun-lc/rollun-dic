<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 23.12.16
 * Time: 12:18 PM
 */

namespace rollun\dic\Example;


use rollun\dic\InsideConstruct;

class SimpleDependency
{
    public $simpleNumericB;
    protected $simpleStringA;
    private $simpleArrayC;

    public function __construct(
        $simpleStringA = 'simpleStringA',
        $simpleNumericB = 2.4,
        $simpleArrayC = [0 => 'simpleArrayC']
    ) {
        InsideConstruct::setConstructParams();
    }

    /**
     * @return mixed
     */
    public function getSimpleStringA()
    {
        return $this->simpleStringA;
    }

    /**
     * @return mixed
     */
    public function getSimpleArrayC()
    {
        return $this->simpleArrayC;
    }
}