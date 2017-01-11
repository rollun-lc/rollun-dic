<?php

/**
 * Zaboy lib (http://zaboy.org/lib/)
 *
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace rollun\dic\Example;

use rollun\dic\InsideConstruct;

class SettersDefault
{

    //$useDi            has has not setter and has not prop

    public $propA;     //has setter and has prop
    private $prop__B;  //has setter and has not prop
    private $propC;    //has not setter and has prop

    public function __construct(&$useDi, $propA = null, $propB = null, $propC = null)
    {
        if ($useDi) {
            $useDi = InsideConstruct::setConstructParams();
        } else {
            $this->propA = $propA;
            $this->prop__B = $propB;
            $this->propC = $propC;
        }
    }

    public function setPropA($param)
    {
        $this->propA = $param;
    }

    public function setPropB($param)
    {
        $this->prop__B = $param;
    }

    public function setProp__C($param)
    {
        $this->propC = $param;
    }

}
