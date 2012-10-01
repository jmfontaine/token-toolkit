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

use TokenToolkit\Search\Pattern\CustomPattern as CustomSearchPattern;
use TokenToolkit\Search\Query as SearchQuery;

/**
 * Class representing a T_CLASS token
 *
 * @package    TokenToolkit
 * @subpackage Token
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class ClassToken extends AbstractTokenWithInnerScope
{
    protected $name = 'T_CLASS';

    public function getFunctions()
    {
        static $functions = null;

        if (null === $functions) {
            $innerScope = $this->getInnerScope();

            $searchPattern = new CustomSearchPattern();
            $searchPattern->addAcceptedTokenType(T_FUNCTION)
                          ->setStartIndex($innerScope->getStartToken()->getIndex())
                          ->setEndIndex($innerScope->getEndToken()->getIndex());

            $query = new SearchQuery($this->getTokenStack(), $searchPattern);
            $resultSet = $query->search();

            $functions = array();
            foreach ($resultSet as $result) {
                $functions[] = $result->getToken();
            }
        }

        return $functions;
    }

    public function getInterfaces()
    {
        static $interfaces = null;

        if (null === $interfaces) {
            $implementsToken = $this->getTokenStack()->findNextTokenByType(T_IMPLEMENTS, $this->getIndex() + 1);
            if (false === $implementsToken) {
                $interfaces = array();
            } else {
                $innerScopeStartIndex = $this->getInnerScope()->getStartToken()->getIndex();
                $tokenStack           = $this->getTokenStack();
                for ($i = $implementsToken->getIndex() + 1; $i < $innerScopeStartIndex; $i++) {
                    $token = $tokenStack[$i];
                    if (T_STRING === $token->getType()) {
                        $interfaces[] = $token;
                    }
                }
            }
        }

        return $interfaces;
    }

    public function isAbstract()
    {
        static $isAbstract = null;

        if (null === $isAbstract) {
            $token = $this->getTokenStack()->findPreviousTokenByType(T_ANY, $this->getIndex() - 1, T_WHITESPACE);
            if (false === $token) {
                $isAbstract = false;
            } else {
                $isAbstract = T_ABSTRACT === $token->getType();
            }
        }

        return $isAbstract;
    }

    public function isFinal()
    {
        static $isFinal = null;

        if (null === $isFinal) {
            $token = $this->getTokenStack()->findPreviousTokenByType(T_ANY, $this->getIndex() - 1, T_WHITESPACE);
            if (false === $token) {
                $isFinal = false;
            } else {
                $isFinal = T_FINAL === $token->getType();
            }
        }

        return $isFinal;
    }
}
