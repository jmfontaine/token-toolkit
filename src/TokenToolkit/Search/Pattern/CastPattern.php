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
class CastPattern extends AbstractPattern
{
    public function __construct()
    {
        $this->addAcceptedTokenType(T_ARRAY_CAST)
             ->addAcceptedTokenType(T_BOOL_CAST)
             ->addAcceptedTokenType(T_DOUBLE_CAST)
             ->addAcceptedTokenType(T_INT_CAST)
             ->addAcceptedTokenType(T_OBJECT_CAST)
             ->addAcceptedTokenType(T_STRING_CAST)
             ->addAcceptedTokenType(T_UNSET_CAST);
    }
}
