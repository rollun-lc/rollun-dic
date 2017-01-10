<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 23.12.16
 * Time: 2:13 PM
 */

namespace rolluncom\dic\Example;

use rolluncom\dic\InsideConstruct;

class InheritanceSimpleDependency extends SimpleDependency
{
    public function __construct($newSimpleStringA = 'simpleString_A')
    {
        InsideConstruct::init(['newSimpleStringA' => 'simpleStringA']);
    }
}
