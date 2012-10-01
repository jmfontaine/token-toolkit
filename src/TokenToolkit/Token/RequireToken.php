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
 * Class representing a T_REQUIRE token
 *
 * @package    TokenToolkit
 * @subpackage Token
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class RequireToken extends AbstractTokenWithoutInnerScope
{
    protected $name = 'T_REQUIRE';

    /**
     * @todo Be smarter and handle paths in variables, constants and concatanated strings
     */
    public function getFilePath()
    {
        static $filePath = null;

        if (null === $filePath) {
            $token = $this->getTokenStack()
                          ->findNextTokenByType(T_CONSTANT_ENCAPSED_STRING, $this->getIndex());

            if (false !== $token) {
                // Remove enclosing quotes before storing
                $filePath = substr($token->getContent(), 1, -1);
            }
        }

        return $filePath;
    }
}
