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
class PhpTagsPattern extends AbstractPattern
{
    public function __construct()
    {
        $this->addAcceptedTokenType(T_OPEN_TAG)
             ->addAcceptedTokenType(T_OPEN_TAG_WITH_ECHO)
             ->addAcceptedTokenType(T_CLOSE_TAG);
    }
}
