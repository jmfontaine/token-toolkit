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
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace PhpTokenToolkit;

use PhpTokenToolkit\Token\TokenInterface;

use PhpTokenToolkit\Search\Query as SearchQuery;
use PhpTokenToolkit\Token\AbstractToken;
use PhpTokenToolkit\Tokenizer\Php as PhpTokenizer;

class TokenStack implements \SeekableIterator
/**
 * Stack of PHP tokens for a string
 *
 * @package PHP Token Toolkit
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
{
    protected $iteratorCursor = 0;

    protected $searchQuery;

    protected $tokenizer;

    protected $tokens = array();

    protected function detectFileEolCharacter($filePath)
    {
        $handle = fopen($filePath, 'r');
        $firstLine = fgets($handle);
        fclose($handle);

        $eolCharacter = substr($firstLine, -1);
        if ($eolCharacter === "\n") {
            $secondLastCharacter = substr($firstLine, -2, 1);
            if ($secondLastCharacter === "\r") {
                $eolCharacter = "\r\n";
            }
        } else if ($eolCharacter !== "\r") {
            // Must not be an EOL char at the end of the line.
            // Probably a one-line file, so assume \n as it really
            // doesn't matter considering there are no newlines.
            $eolCharacter = "\n";
        }

        return $eolCharacter;
    }

    protected function getTokenClass($tokenType)
    {
        if ($this->getTokenizer()->isCustom(constant($tokenType))) {
            $subNamespace = 'Custom';
        } else {
            $subNamespace = 'Php';
        }

        $tokenType = substr($tokenType, 2);
        $tokenType = strtolower($tokenType);

        $result = "PhpTokenToolkit\Token\\$subNamespace\\";
        foreach (explode('_', $tokenType) as $word) {
            $result .= ucfirst($word);
        }
        $result .= 'Token';

        return $result;
    }

    protected function getSearchQuery()
    {
        if (null === $this->searchQuery) {
            $this->searchQuery = new SearchQuery($this);
        }

        return $this->searchQuery;
    }

    protected function processSourceFile($source)
    {
        $content      = file_get_contents($source);
        $eolCharacter = $this->detectFileEolCharacter($source);

        return $this->processSourceString($content, $eolCharacter);
    }

    protected function processSourceString($source, $eolCharacter = "\n")
    {
        if (!is_string($source)) {
            throw new \InvalidArgumentException('Source must be a string');
        }

        $tokens = $this->getTokenizer()->getTokens($source, $eolCharacter);
        foreach ($tokens as $tokenIndex => $token) {
            $tokenType  = $this->getTokenizer()->getTokenName($token['type']);
            $tokenClass = $this->getTokenClass($tokenType);

            $this->tokens[] = new $tokenClass(
                $tokenIndex,
                $token['content'],
                $token['startLine'],
                $token['startColumn'],
                $token['endLine'],
                $token['endColumn'],
                $this
            );
            $tokenIndex++;
        }

        // Let search query know that the token stack has been updated
        $this->getSearchQuery()->setTokenStack($this);
    }

    public function __construct($source, $eolCharacter = null)
    {
        if (file_exists($source)) {
            $this->processSourceFile($source);
        } else {
            $this->processSourceString($source, $eolCharacter);
        }
    }

    public function getTokenName(AbstractBaseToken $token)
    {
        return $this->getTokenizer()->getTokenName($token->getCode());
    }

    public function getTokenizer()
    {
        if (null === $this->tokenizer) {
            $this->tokenizer = new PhpTokenizer();
        }

        return $this->tokenizer;
    }

    public function getTokens()
    {
        return $this->tokens;
    }

    public function search(array $criterias, $limit = null)
    {
        return $this->searchQuery->search($criterias, $limit);
    }

    /*
     * Iterator methods
     */
    public function current()
    {
        return $this->tokens[$this->iteratorCursor];
    }

    public function key()
    {
        return $this->iteratorCursor;
    }

    public function next()
    {
        $this->iteratorCursor++;
    }

    public function rewind()
    {
        $this->iteratorCursor = 0;
    }

    public function valid()
    {
        return array_key_exists($this->iteratorCursor, $this->tokens);
    }

    /*
     * SeekableIterator method
     */

    /**
     * (non-PHPdoc)
     * @see SeekableIterator::seek()
     */
    public function seek($position) {
      $this->iteratorCursor = $position;

      if (!$this->valid()) {
          throw new \OutOfBoundsException("Invalid seek position ($position)");
      }

      return $this;
    }
}