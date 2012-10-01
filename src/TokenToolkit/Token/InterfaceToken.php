<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package    TokenToolkit
 * @subpackage Token
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace TokenToolkit\Token;

use TokenToolkit\Search\Pattern\CustomPattern;
use TokenToolkit\Search\Query as SearchQuery;

/**
 * Class representing a T_INTERFACE token
 *
 * @package    TokenToolkit
 * @subpackage Token
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class InterfaceToken extends AbstractTokenWithInnerScope
{
    protected $name = 'T_INTERFACE';

    public function getInterfaceName()
    {
        static $interfaceName = null;

        if (null === $interfaceName) {
            $token = $this->getTokenStack()
                          ->findNextTokenByType(T_STRING, $this->getIndex());

            if (false !== $token) {
                $interfaceName = $token->getContent();
            }
        }

        return $interfaceName;
    }
}
