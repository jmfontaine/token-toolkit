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
 * @package    TokenToolkit
 * @subpackage Token
 * @author     Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License */
namespace TokenToolkit\Token;

/**
 * Abstract class for tokens with inner scope.
 *
 * This class is the base for every token classes that have an inner scope, that is to
 * say sub-elements like classes and functions.
 *
 * @package    TokenToolkit
 * @subpackage Token
 * @author     Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License */
abstract class AbstractTokenWithInnerScope extends AbstractTokenWithoutInnerScope
{
    public function getInnerScope()
    {
        static $innerScope = null;

        if (null === $innerScope) {
            $tokenStack = $this->getTokenStack();
            $startToken = $tokenStack->findNextTokenByType(T_OPEN_CURLY_BRACKET, $this->getIndex());

            $count = 1;
            for ($i = $startToken->getIndex() + 1; ; $i++) {
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
