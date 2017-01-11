<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 23.12.16
 * Time: 12:18 PM
 */

namespace rollun\dic\Example;

use rollun\dic\InsideConstruct;

class StaticDependencyParent
{
    public $simpleNumericB;
    protected $simpleStringA;
    private $simpleArrayC;
    private $constStatic;

    const CONST_VAL = 'val_parent';

    public function __construct(
        $simpleStringA = 'simpleStringA',
        $simpleNumericB = 2.4,
        $simpleArrayC = [0 => 'simpleArrayC']
    ) {
        $this->simpleArrayC = $simpleArrayC;
        $this->simpleNumericB = $simpleNumericB;
        $this->simpleStringA = $simpleStringA;

        $this->constStatic = static::CONST_VAL;
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

    /**
     * @return string
     */
    public function getConstStatic()
    {
        return $this->constStatic;
    }
}