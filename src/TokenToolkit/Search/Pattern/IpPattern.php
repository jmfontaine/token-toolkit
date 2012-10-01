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
class IpPattern extends AbstractPattern
{
    public function __construct()
    {
        $this->addAcceptedTokenType(T_CONSTANT_ENCAPSED_STRING);
    }

    public function match(TokenInterface $token, $direction = SearchQuery::FORWARD)
    {
        if (false === parent::match($token, $direction)) {
            return false;
        }

        return false !== filter_var($token->getContent(), FILTER_VALIDATE_IP);
    }
}
