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

namespace PhpTokenToolkit\Search\Pattern;

use PhpTokenToolkit\Search\Query as SearchQuery;
use PhpTokenToolkit\Search\Pattern\PatternInterface as SearchPatternInterface;
use PhpTokenToolkit\Token\TokenInterface;

// KLUDGE: We need to include the PHP tokenizer file to make sure that custom constants are defined.
// Otherwise it may not be loaded yet by the time this file is loaded depending on the order
// of the classes instanciations.
require_once 'PhpTokenToolkit/Tokenizer/Php.php';

/**
 *
 *
 * @package PhpTokenToolkit
 * @subpackage Search
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
abstract class AbstractPattern implements SearchPatternInterface
{
    protected $acceptAnyTokenType = false;

    protected $acceptedTokenTypes = array();

    protected $content;

    protected $contentIsRegex = false;

    protected $endIndex = null;

    protected $endLine = null;

    protected $excludedTokenTypes = array();

    protected $name;

    protected $startIndex = null;

    protected $startLine = null;

    public function getName()
    {
        return null === $this->name ? get_class($this) : $this->name;
    }

    protected function addAcceptedTokenType($tokenType)
    {
        if (T_ANY === $tokenType) {
            $this->acceptAnyTokenType = true;
        } elseif (!in_array($tokenType, $this->acceptedTokenTypes)) {
            $this->acceptedTokenTypes[] = $tokenType;
        }

        return $this;
    }

    protected function addExcludedTokenType($tokenType)
    {
        if (!in_array($tokenType, $this->excludedTokenTypes)) {
            $this->excludedTokenTypes[] = $tokenType;
        }

        return $this;
    }

    public function match(TokenInterface $token, $direction = SearchQuery::FORWARD)
    {
        if (null !== $this->startIndex) {
            if (($direction == SearchQuery::BACKWARD && $token->getIndex() > $this->startIndex) ||
                ($direction == SearchQuery::FORWARD && $token->getIndex() < $this->startIndex)) {
                return false;
            }
        }

        if (null !== $this->endIndex) {
            if (($direction == SearchQuery::BACKWARD && $token->getIndex() < $this->endIndex) ||
                ($direction == SearchQuery::FORWARD && $token->getIndex() > $this->endIndex)) {
                return false;
            }
        }

        if (null !== $this->startLine && $token->getStartLine() < $this->startLine) {
            return false;
        }

        if (null !== $this->endLine && $token->getEndLine() > $this->endLine) {
            return false;
        }

        if (null !== $this->content) {
            if ($this->contentIsRegex) {
                if (0 === preg_match($this->content, $token->getContent())) {
                    return false;
                }
            } elseif ($token->getContent() != $this->content) {
                return false;
            }
        }

        if (!$this->acceptAnyTokenType && !in_array($token->getType(), $this->acceptedTokenTypes)) {
            return false;
        }

        if (in_array($token->getType(), $this->excludedTokenTypes)) {
            return false;
        }

        return true;
    }

    public function setContent($content, $isRegex = false)
    {
        if (!is_string($content)) {
            throw new \InvalidArgumentException("Invalid content ($content)");
        }

        if (!is_bool($isRegex)) {
            throw new \InvalidArgumentException("Invalid boolean value ($isRegex)");
        }

        $this->content        = $content;
        $this->contentIsRegex = $isRegex;

        return $this;
    }

    public function setEndIndex($index)
    {
        if (!is_int($index) || 0 > $index) {
            throw new \InvalidArgumentException("Invalid end index ($index)");
        }

        $this->endIndex = $index;

        return $this;
    }

    public function setEndLine($line)
    {
        if (!is_int($line) || 1 > $line) {
            throw new \InvalidArgumentException("Invalid end line ($line)");
        }

        $this->endLine = $line;

        return $this;
    }

    public function setName($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException("Invalid name ($name)");
        }

        $this->name = $name;

        return $this;
    }

    public function setStartIndex($index)
    {
        if (!is_int($index) || 0 > $index) {
            throw new \InvalidArgumentException("Invalid start index ($index)");
        }

        $this->startIndex = $index;

        return $this;
    }

    public function setStartLine($line)
    {
        if (!is_int($line) || 1 > $line) {
            throw new \InvalidArgumentException("Invalid start line ($line)");
        }

        $this->startLine = $line;

        return $this;
    }
}
