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
class ControlStructuresPattern extends AbstractPattern
{
    public function __construct()
    {
        /**
         * @see http://www.php.net/manual/en/language.control-structures.php
         */
        $this->addAcceptedTokenType(T_IF)
             ->addAcceptedTokenType(T_ELSE)
             ->addAcceptedTokenType(T_ELSEIF)
             ->addAcceptedTokenType(T_ENDIF)
             ->addAcceptedTokenType(T_DO)
             ->addAcceptedTokenType(T_WHILE)
             ->addAcceptedTokenType(T_ENDWHILE)
             ->addAcceptedTokenType(T_FOR)
             ->addAcceptedTokenType(T_ENDFOR)
             ->addAcceptedTokenType(T_FOREACH)
             ->addAcceptedTokenType(T_ENDFOREACH)
             ->addAcceptedTokenType(T_BREAK)
             ->addAcceptedTokenType(T_CONTINUE)
             ->addAcceptedTokenType(T_SWITCH)
             ->addAcceptedTokenType(T_ENDSWITCH)
             ->addAcceptedTokenType(T_DECLARE)
             ->addAcceptedTokenType(T_ENDDECLARE)
             ->addAcceptedTokenType(T_RETURN)
             ->addAcceptedTokenType(T_REQUIRE)
             ->addAcceptedTokenType(T_REQUIRE_ONCE)
             ->addAcceptedTokenType(T_INCLUDE)
             ->addAcceptedTokenType(T_INCLUDE_ONCE)
             ->addAcceptedTokenType(T_GOTO);
    }
}
