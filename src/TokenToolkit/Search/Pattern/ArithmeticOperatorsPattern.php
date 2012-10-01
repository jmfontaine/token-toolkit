<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package TokenToolkit
 * @subpackage Search
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace TokenToolkit\Search\Pattern;

/**
 *
 *
 * @package TokenToolkit
 * @subpackage Search
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class ArithmeticOperatorsPattern extends AbstractPattern
{
    public function __construct()
    {
        $this->addAcceptedTokenType(T_DIVIDE)
             ->addAcceptedTokenType(T_MINUS)
             ->addAcceptedTokenType(T_MULTIPLY)
             ->addAcceptedTokenType(T_MODULUS)
             ->addAcceptedTokenType(T_PLUS)
             ->addAcceptedTokenType(T_POWER);
    }
}
