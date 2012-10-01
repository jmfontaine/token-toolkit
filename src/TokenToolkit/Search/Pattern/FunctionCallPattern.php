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
class FunctionCallPattern extends AbstractPattern
{
    public function __construct($functionName = null)
    {
        $this->addAcceptedTokenType(T_STRING)
             ->setCallback(
                 function ($token) {
                    $nextToken = $token->getTokenStack()
                                           ->findNextTokenByType(T_ANY, $token->getIndex() + 1, null, T_WHITESPACE);
                    return T_OPEN_PARENTHESIS === $nextToken->getType();
                 }
             );

        if (null !== $functionName) {
             $this->setContent($functionName);
        }
    }
}
