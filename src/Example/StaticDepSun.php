<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11.01.17
 * Time: 16:33
 */

namespace rollun\dic\Example;

use rollun\dic\InsideConstruct;

class StaticDepSun extends StaticDependencyParent
{
    public function __construct($simpleStringA = 'simpleStringA', $simpleNumericB = 2.4, array $simpleArrayC = [0 => 'simpleArrayC'])
    {
        InsideConstruct::init();
    }

    const CONST_VAL = 'val_sun';
}
