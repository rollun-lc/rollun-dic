<?php

/**
 * Zaboy lib (http://zaboy.org/lib/)
 *
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace rollun\dic\Example;

use rollun\dic\InsideConstruct;

class SimpleWithSeterAndConstruct
{
//    //From parent
//    public $propA;
//    protected $propB;
//    private $propC;

    private $propA;

    private $setterProp;

    public function __construct($propA = null)
    {
        InsideConstruct::setConstructParams(['setterProp' => 'propB', 'propA' => 'propB']);
    }

    /**
     * @param mixed $setterProp
     */
    public function setSetterProp($setterProp)
    {
        $this->setterProp = $setterProp;
    }

    /**
     * @return mixed
     */
    public function getPropA()
    {
        return $this->propA;
    }

    /**
     * @return mixed
     */
    public function getSetterProp()
    {
        return $this->setterProp;
    }
}
