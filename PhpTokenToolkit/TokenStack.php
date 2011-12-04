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
 * @package   PhpTokenToolkit
 * @author    Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace PhpTokenToolkit;

use PhpTokenToolkit\File\File;
use PhpTokenToolkit\Search\Pattern\CustomPattern as CustomSearchPattern;
use PhpTokenToolkit\Search\Query as SearchQuery;
use PhpTokenToolkit\Token\AbstractToken;
use PhpTokenToolkit\Token\TokenInterface;
use PhpTokenToolkit\Tokenizer\Php as PhpTokenizer;

/**
 * This class allows to manipulate a stack of tokens extracted for PHP Code.
 *
 * @package    PhpTokenToolkit
 * @subpackage TokenStack
 * @copyright  2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @author     Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class TokenStack implements \ArrayAccess, \Countable, \SeekableIterator
{
    /**
     * Instance of PHPTokenTookit\File\File in case the stack has been built from a file
     *
     * @var PHPTokenTookit\File\File
     */
    protected $file;

    /**
     * Cursor used for tokens iteration
     *
     * @var int
     */
    protected $iteratorCursor = 0;

    /**
     * Tokenizer used to tokenize source code
     *
     * @var PHPTokenTookit\Tokenizer\Php
     */
    protected $tokenizer;

    /**
     * Array of tokens extracted for the source code
     *
     * @var array of PHPTokenTookit\Token\TokenInterface
     */
    protected $tokens = array();

    /**
     * Returns the name of the class corresponding to a token type.
     *
     * @param int $tokenType Type of the token to retrieve class for
     * @return string Name of the class
     */
    protected function getTokenClass($tokenType)
    {
        $tokenType = substr($tokenType, 2);
        $tokenType = strtolower($tokenType);

        $result = "PhpTokenToolkit\Token\\";
        foreach (explode('_', $tokenType) as $word) {
            $result .= ucfirst($word);
        }
        $result .= 'Token';

        return $result;
    }

    /**
     * Extracts a PHP token stack from a PHPTokenTookit\File\File instance.
     *
     * @param PHPTokenTookit\File\File $file Source file
     * @return void
     */
    protected function processSourceFile(File $file)
    {
        $content      = $file->getSource();
        $eolCharacter = $file->getEolCharacter();

        $this->processSourceString($content, $eolCharacter);
    }

    /**
     * Extracts a PHP token stack from a string.
     *
     * @param string $source Source code to extract tokens from
     * @param string $eolCharacter End of line character
     * @throws \InvalidArgumentException If source is not a string
     * @return void
     */
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
                $token['level'],
                $this
            );
            $tokenIndex++;
        }
    }

    /**
     * Sets PHP tokens from an array.
     *
     * @param array $tokens Array of tokens
     * @return void
     */
    protected function processTokens(array $tokens)
    {
        // TODO: Check tokens before using them
        $this->tokens = $tokens;
    }

    /**
     * Class constructor. Loads tokens from a source that can be an instance of PHPTokenTookit\File\File,
     * a string or an array of tokens.
     *
     * @param string $source Source code to extract tokens from
     * @param string $eolCharacter End of line character
     * @return void
     */
    public function __construct($source, $eolCharacter = null)
    {
        if ($source instanceof File) {
            $this->file = $source;
            $this->processSourceFile($source);
        } elseif (is_array($source)) {
            $this->processTokens($source);
        } else {
            $this->processSourceString($source, $eolCharacter);
        }
    }

    /**
     * Returns a part of the token stack.
     *
     * @param int $startIndex Start index for the extraction
     * @param int $endIndex End index for the extraction
     * @return PHPTokenTookit\TokenStack The extracted token stack
     */
    public function extractTokenStack($startIndex, $endIndex)
    {
        $tokens = array_slice($this->tokens, $startIndex, $endIndex - $startIndex + 1);

        return new TokenStack($tokens);
    }

    /**
     * Returns first encountered token matching the criteria. If none is found then false is returned.
     *
     * @param int|array $type Type or array of types the token must match.
     * @param int $direction Direction of the search. It can be either PhpTokenToolkit\Search\Query::BACKWARD
     *                       or PhpTokenToolkit\Search\Query::FORWARD.
     * @param int $startIndex Index of the token to start the search from
     * @param int $endIndex Index of the token where to end the search
     * @param int|array $excludedType Type or array of types the token must not match.
     * @return
     */
    public function findFirstTokenByType($type, $direction, $startIndex = null, $endIndex = null, $excludedType = null)
    {
        // Make sure we get an array in the end
        $types = (array) $type;

        $pattern = new CustomSearchPattern();

        if (null !== $startIndex) {
            $pattern->setStartIndex($startIndex);
        }

        if (null !== $endIndex) {
            $pattern->setEndIndex($endIndex);
        }

        if (null !== $excludedType) {
            $excludedTypes = (array) $excludedType;
            foreach ($excludedTypes as $excludedType) {
                $pattern->addExcludedTokenType($excludedType);
            }
        }

        foreach ($types as $type) {
            $pattern->addAcceptedTokenType($type);
        }

        $query     = new SearchQuery($this);
        $resultSet = $query->addSearchPattern($pattern)
                           ->setLimit(1)
                           ->setDirection($direction)
                           ->search();

        if (0 < count($resultSet)) {
            return $resultSet[0]->getToken();
        } else {
           return false;
        }
    }

    public function findNextTokenByType($type, $startIndex = null, $endIndex = null, $excludedType = null)
    {
        return $this->findFirstTokenByType($type, SearchQuery::FORWARD, $startIndex, $endIndex, $excludedType);
    }

    public function findPreviousTokenByType($type, $startIndex = null, $endIndex = null, $excludedType = null)
    {
        return $this->findFirstTokenByType($type, SearchQuery::BACKWARD, $startIndex, $endIndex, $excludedType);
    }

    /**
     *
     * @return PhpTokenToolkit\Token\Token Token ending the stack
     */
    public function getEndToken()
    {
        $endTokenIndex = count($this->tokens) - 1;

        return $this->tokens[$endTokenIndex];
    }

    public function getFile()
    {
        return $this->file;
    }

    /**
     *
     * @return PhpTokenToolkit\Token\Token Token starting the stack
     */
    public function getStartToken()
    {
        return $this->tokens[0];
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

    public function toArray()
    {
        $data = array();

        foreach ($this as $token) {
            $data[] = $token->toArray();
        }

        return $data;
    }

    /*
     * ArrayAccess interface methods
    */

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->tokens);
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
     *
     * @see SeekableIterator::seek()
     */
    public function seek($position)
    {
        $this->iteratorCursor = $position;

        if (!$this->valid()) {
            throw new \OutOfBoundsException("Invalid seek position ($position)");
        }

        return $this;
    }
}