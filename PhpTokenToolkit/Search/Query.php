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
 * @package PhpTokenToolkit
 * @subpackage Search
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace PhpTokenToolkit\Search;

use PhpTokenToolkit\Search\Result\Result;
use PhpTokenToolkit\Search\Result\ResultSet;
use PhpTokenToolkit\Search\Pattern\PatternInterface as SearchPatternInterface;
use PhpTokenToolkit\Token\TokenInterface;
use PhpTokenToolkit\TokenStack;

/**
 * Query for PHP token search
 *
 * @package PhpTokenToolkit
 * @subpackage Search
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class Query
{
    const BACKWARD = -1;
    const FORWARD  = 1;

    protected $direction = self::FORWARD;

    protected $limit;

    protected $searchPatterns = array();

    protected $tokenStack;

    public function __construct(TokenStack $tokenStack, $searchPatterns = array())
    {
        if (is_object($searchPatterns)) {
            $searchPatterns = array($searchPatterns);
        }

        $this->searchPatterns = $searchPatterns;
        $this->tokenStack     = $tokenStack;
    }

    public function addSearchPattern(SearchPatternInterface $searchPattern)
    {
        $this->searchPatterns[] = $searchPattern;

        return $this;
    }

    public function getDirection()
    {
        return $this->direction;
    }

    public function getTokenStack()
    {
        return $this->tokenStack;
    }

    public function search()
    {
        $resultSet = new ResultSet();

        $tokensCount  = count($this->getTokenStack());

        // If the stack is empty return now
        if (0 === $tokensCount) {
            return $resultSet;
        }

        if (self::FORWARD === $this->direction) {
            $startIndex = 0;
            $endIndex   = $tokensCount - 1;
        } else {
            $startIndex = $tokensCount - 1;
            $endIndex   = 0;
        }

        $resultsCount = 0;
        for ($i = $startIndex; ;) {
            $token = $this->tokenStack[$i];

            foreach ($this->searchPatterns as $pattern) {
                if ($pattern->match($token, $this->direction)) {
                    $resultSet->add(new Result($token, $pattern));
                    $resultsCount++;

                    // Stop if we have enough results
                    if (null !== $this->limit && $resultsCount >= $this->limit) {
                        break 2;
                    }
                }
            }

            // Handle end of loop and incrementation/decrementation
            if (self::FORWARD === $this->direction) {
                if ($i >= $endIndex) {
                    break;
                } else {
                    $i++;
                }
            } else {
                if ($i <= $endIndex) {
                    break;
                } else {
                    $i--;
                }
            }
        }

        return $resultSet;
    }

    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function setTokenStack(TokenStack $tokenStack)
    {
        $this->tokenStack = $tokenStack;

        return $this;
    }
}
