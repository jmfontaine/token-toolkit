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

use PhpTokenToolkit\File\File;
use PhpTokenToolkit\Token\AbstractToken;
use PhpTokenToolkit\Token\TokenInterface;
use PhpTokenToolkit\Tokenizer\Php as PhpTokenizer;

class TokenStack implements \ArrayAccess, \Countable, \SeekableIterator
/**
 * Stack of PHP tokens for a string
 *
 * @package PHP Token Toolkit
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
{
    protected $file;

    protected $iteratorCursor = 0;

    protected $tokenizer;

    protected $tokens = array();

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

    protected function processSourceFile(File $file)
    {
        $content      = $file->getSource() ;
        $eolCharacter = $file->getEolCharacter();

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
    }

    public function __construct($source, $eolCharacter = null)
    {
        if ($source instanceof File) {
            $this->file = $source;
            $this->processSourceFile($source);
        } else {
            $this->processSourceString($source, $eolCharacter);
        }
    }

    public function getFile()
    {
        return $this->file;
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

    /*
     * ArrayAccess interface methods
    */

    public function offsetExists($offset)
    {
        return isset($this->tokens[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->tokens[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->tokens[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->tokens[$offset]);
    }

    /*
     * Countable interface methods
     */
    public function count()
    {
        return count($this->tokens);
    }

    /*
     * Iterator interface methods
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
     * SeekableIterator interface method
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