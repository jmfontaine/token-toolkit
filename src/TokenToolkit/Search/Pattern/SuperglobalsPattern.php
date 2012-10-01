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

use TokenToolkit\Search\Query as SearchQuery;
use TokenToolkit\Token\TokenInterface;

/**
 *
 *
 * @package TokenToolkit
 * @subpackage Search
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class SuperglobalsPattern extends AbstractPattern
{
    public function __construct()
    {
        $this->addAcceptedTokenType(T_VARIABLE);
    }

    public function match(TokenInterface $token, $direction = SearchQuery::FORWARD)
    {
        if (false === parent::match($token, $direction)) {
            return false;
        }

        $superglobalsNames = array(
        	'$_COOKIE',
            '$_ENV',
            '$_FILES',
        	'$_GET',
            '$_POST',
            '$_REQUEST',
            '$_SERVER',
            '$_SESSION',
            '$GLOBALS',
        );
        return in_array($token->getContent(), $superglobalsNames);
    }
}
