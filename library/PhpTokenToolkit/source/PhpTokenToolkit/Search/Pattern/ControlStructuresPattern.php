<?php
/**
 * Copyright (c) 2011, Jean-Marc Fontaine
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name PHP Token Toolkit nor the
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
 * @package PHP Token Toolkit
 * @subpackage Search/Pattern
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace PhpTokenToolkit\Search\Pattern;

/**
 *
 *
 * @package PHP Token Toolkit
 * @subpackage Search/Pattern
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class ControlStructuresPattern extends AbstractPattern
{
    public function __construct()
    {
        /**
         * @see http://www.php.net/manual/en/language.control-structures.php
         */
        $this->addTokenType(T_IF)
             ->addTokenType(T_ELSE)
             ->addTokenType(T_ELSEIF)
             ->addTokenType(T_ENDIF)
             ->addTokenType(T_DO)
             ->addTokenType(T_WHILE)
             ->addTokenType(T_ENDWHILE)
             ->addTokenType(T_FOR)
             ->addTokenType(T_ENDFOR)
             ->addTokenType(T_FOREACH)
             ->addTokenType(T_ENDFOREACH)
             ->addTokenType(T_BREAK)
             ->addTokenType(T_CONTINUE)
             ->addTokenType(T_SWITCH)
             ->addTokenType(T_ENDSWITCH)
             ->addTokenType(T_DECLARE)
             ->addTokenType(T_ENDDECLARE)
             ->addTokenType(T_RETURN)
             ->addTokenType(T_REQUIRE)
             ->addTokenType(T_REQUIRE_ONCE)
             ->addTokenType(T_INCLUDE)
             ->addTokenType(T_INCLUDE_ONCE)
             ->addTokenType(T_GOTO);
    }
}
