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

/**
 * Class representing a T_FUNCTION token
 *
 * @package    TokenToolkit
 * @subpackage Token
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class FunctionToken extends AbstractTokenWithInnerScope
{
    protected $functionName;

    protected $name = 'T_FUNCTION';

    public function getFunctionName()
    {
        if (null === $this->functionName) {
            $token = $this->getTokenStack()
                          ->findNextTokenByType(T_STRING, $this->getIndex());

            if (false !== $token) {
                $this->functionName = $token->getContent();
            }
        }

        return $this->functionName;
    }
}
