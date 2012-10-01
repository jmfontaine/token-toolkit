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
class ComparisonOperatorsPattern extends AbstractPattern
{
    public function __construct()
    {
        $this->addAcceptedTokenType(T_GREATER_THAN)
             ->addAcceptedTokenType(T_IS_EQUAL)
             ->addAcceptedTokenType(T_IS_GREATER_OR_EQUAL)
             ->addAcceptedTokenType(T_IS_IDENTICAL)
             ->addAcceptedTokenType(T_IS_NOT_EQUAL)
             ->addAcceptedTokenType(T_IS_NOT_IDENTICAL)
             ->addAcceptedTokenType(T_IS_SMALLER_OR_EQUAL)
             ->addAcceptedTokenType(T_LESS_THAN);
    }
}
