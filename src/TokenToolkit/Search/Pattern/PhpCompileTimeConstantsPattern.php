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
class PhpCompileTimeConstantsPattern extends AbstractPattern
{
    public function __construct()
    {
        $this->addAcceptedTokenType(T_CLASS_C)
             ->addAcceptedTokenType(T_DIR)
             ->addAcceptedTokenType(T_FILE)
             ->addAcceptedTokenType(T_FUNC_C)
             ->addAcceptedTokenType(T_LINE)
             ->addAcceptedTokenType(T_METHOD_C)
             ->addAcceptedTokenType(T_NS_C);
    }
}
