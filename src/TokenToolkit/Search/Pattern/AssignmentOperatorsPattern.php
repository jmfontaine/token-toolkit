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
class AssignmentOperatorsPattern extends AbstractPattern
{
    public function __construct()
    {
        $this->addAcceptedTokenType(T_AND_EQUAL)
             ->addAcceptedTokenType(T_CONCAT_EQUAL)
             ->addAcceptedTokenType(T_DOUBLE_ARROW)
             ->addAcceptedTokenType(T_DIV_EQUAL)
             ->addAcceptedTokenType(T_EQUAL)
             ->addAcceptedTokenType(T_MINUS_EQUAL)
             ->addAcceptedTokenType(T_MOD_EQUAL)
             ->addAcceptedTokenType(T_MUL_EQUAL)
             ->addAcceptedTokenType(T_PLUS_EQUAL)
             ->addAcceptedTokenType(T_XOR_EQUAL);
    }
}
