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

// KLUDGE: We need to include the PHP tokenizer file to make sure that custom constants are defined.
// Otherwise it may not be loaded yet by the time this file is loaded depending on the order
// of the classes instanciations.
require_once 'TokenToolkit/Tokenizer/Php.php';

/**
 *
 *
 * @package TokenToolkit
 * @subpackage Search
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class ErrorControlPattern extends AbstractPattern
{
    public function __construct()
    {
        $this->addAcceptedTokenType(T_ERROR_CONTROL);
    }
}
