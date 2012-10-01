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
class StringsPattern extends AbstractPattern
{
    public function __construct()
    {
        $this->addAcceptedTokenType(T_CONSTANT_ENCAPSED_STRING)
             ->addAcceptedTokenType(T_DOUBLE_QUOTED_STRING)
             ->addAcceptedTokenType(T_STRING);
    }
}
