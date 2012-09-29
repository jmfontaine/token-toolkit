<?php
/**
 * Copyright (c) 2011-2012, Jean-Marc Fontaine
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name Token Toolkit nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL Jean-Marc Fontaine BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @package TokenToolkit
 * @subpackage Search
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace TokenToolkit\Search\Pattern;

/**
 *
 *
 * @package TokenToolkit
 * @subpackage Search
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
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
