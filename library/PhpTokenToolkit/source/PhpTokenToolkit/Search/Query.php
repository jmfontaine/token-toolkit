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
 * @subpackage Search
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace PhpTokenToolkit\Search;

use PhpTokenToolkit\TokenStack;
use PhpTokenToolkit\Token\TokenInterface;

/**
 * Query for PHP token search
 *
 * @package PHP Token Toolkit
 * @subpackage Search
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class Query
{
    protected $tokenStack;

    protected $type;

    protected function tokenMatchesSearchQuery(array $criterias,
        TokenInterface $token)
    {
        $match = true;

        foreach ($criterias as $name => $value) {
            switch ($name) {
                case 'content':
                    $match = $value === $token->getContent();
                    break;

                case 'end':
                    $match = $value >= $token->getIndex();
                    break;

                case 'regex':
                    $match = 1 === preg_match($value, $token->getContent());
                    break;

                case 'start':
                    $match = $value <= $token->getIndex();
                    break;

                case 'type':
                    $match = in_array($token->getType(), (array) $value);
                    break;

                default:
                    throw new \InvalidArgumentException(
                    	"Unknown criteria ($name)"
                    );
            }

            // If a criteria does not mached then this token is not
            // what we are looking for
            if (false === $match) {
                break;
            }
        }

        return $match;
    }

    public function __construct(TokenStack $tokenStack)
    {
        $this->tokenStack = $tokenStack;
    }

    public function getTokenStack()
    {
        return $this->tokenStack;
    }

    public function search($criterias, $limit = null)
    {
        $resultSet = new ResultSet();

        reset($this->tokenStack);
        foreach ($this->tokenStack as $token) {
            if ($this->tokenMatchesSearchQuery($criterias, $token)) {
                $resultSet->add($token);

                // Stop if we have enough results
                if (null !== $limit && count($resultSet) >= $limit) {
                    break;
                }
            }
        }

        return $resultSet;
    }

    public function setTokenStack(TokenStack $tokenStack)
    {
        $this->tokenStack = $tokenStack;
    }
}
