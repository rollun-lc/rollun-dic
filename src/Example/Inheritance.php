<?php

/**
 * Zaboy lib (http://zaboy.org/lib/)
 *
 * @copyright  Zaboychenko Andrey
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace rolluncom\dic\Example;

use rolluncom\dic\InsideConstruct;

class Inheritance extends PropertiesDefault
{
//    //From parent
//    public $propA;
//    protected $propB;
//    private $propC;

    public function __construct($newPropA = null)
    {
        $result = InsideConstruct::setConstructParams();
        InsideConstruct::runParentConstruct(['useDi' => true, 'propA' => $result['newPropA']]);
    }
}
