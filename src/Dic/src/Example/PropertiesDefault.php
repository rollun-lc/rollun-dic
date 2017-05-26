<?php

/**
 * Zaboy lib (http://zaboy.org/lib/)
 *
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace rollun\dic\Example;

use rollun\dic\InsideConstruct;

class PropertiesDefault
{

    public $propA;
    protected $propB;
    private $propC;

    public function __construct($useDi, $propA = null, $propB = null, $propC = null)
    {
        if ($useDi) {
            InsideConstruct::setConstructParams();
        } else {
            $this->propA = $propA;
            $this->propB = $propB;
            $this->propC = $propC;
        }
    }

}
