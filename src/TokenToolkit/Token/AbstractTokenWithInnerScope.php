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
 * Abstract class for tokens with inner scope.
 *
 * This class is the base for every token classes that have an inner scope, that is to
 * say sub-elements like classes and functions.
 *
 * @package    TokenToolkit
 * @subpackage Token
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
abstract class AbstractTokenWithInnerScope extends AbstractTokenWithoutInnerScope
{
    public function getInnerScope()
    {
        static $innerScope = null;

        if (null === $innerScope) {
            $tokenStack = $this->getTokenStack();
            $startToken = $tokenStack->findNextTokenByType(T_OPEN_CURLY_BRACKET, $this->getIndex());

            $count = 1;
            for ($i = $startToken->getIndex() + 1; /* Ommited on purpose */; $i++) {
                $tokenType = $tokenStack[$i]->getType();

                if (T_OPEN_CURLY_BRACKET === $tokenType) {
                    $count++;
                } elseif (T_CLOSE_CURLY_BRACKET === $tokenType) {
                    $count--;
                }

                if (0 === $count) {
                    break;
                }
            }
            $endToken = $tokenStack[$i];

            $innerScope = $tokenStack->extractTokenStack($startToken->getIndex(), $endToken->getIndex());
        }

        return $innerScope;
    }
}
