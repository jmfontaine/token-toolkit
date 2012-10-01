<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package   TokenToolkit
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace TokenToolkit;

use TokenToolkit\File\File;
use TokenToolkit\Search\Pattern\CustomPattern as CustomSearchPattern;
use TokenToolkit\Search\Query as SearchQuery;
use TokenToolkit\Token\AbstractToken;
use TokenToolkit\Token\TokenInterface;
use TokenToolkit\Tokenizer\Php as PhpTokenizer;

/**
 * This class allows to manipulate a stack of tokens extracted for PHP Code.
 *
 * @package    TokenToolkit
 * @subpackage TokenStack

 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class TokenStack implements \ArrayAccess, \Countable, \SeekableIterator
{
    /**
     * Instance of TokenToolkit\File\File in case the stack has been built from a file
     *
     * @var TokenToolkit\File\File
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
     * @var TokenToolkit\Tokenizer\Php
     */
    protected $tokenizer;

    /**
     * Array of tokens extracted for the source code
     *
     * @var array of TokenToolkit\Token\TokenInterface
     */
    protected $tokens = array();

    /**
     * Returns the name of the class corresponding to a token type.
     *
     * @param int $tokenType Type of the token to retrieve class for
     *
     * @return string Name of the class
     */
    protected function getTokenClass($tokenType)
    {
        $tokenType = substr($tokenType, 2);
        $tokenType = strtolower($tokenType);

        $result = "TokenToolkit\Token\\";
        foreach (explode('_', $tokenType) as $word) {
            $result .= ucfirst($word);
        }
        $result .= 'Token';

        return $result;
    }

    /**
     * Extracts a PHP token stack from a TokenToolkit\File\File instance.
     *
     * @param TokenToolkit\File\File $file Source file
     *
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
     * @param string $source       Source code to extract tokens from
     * @param string $eolCharacter End of line character
     *
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
     *
     * @return void
     */
    protected function processTokens(array $tokens)
    {
        $newTokens = array();
        foreach ($tokens as $token) {
            if (!$token instanceof TokenInterface) {
                throw new \InvalidArgumentException('An array of tokens must be passed as an argument');
            }

            $class = get_class($token);
            $newTokens[] = new $class(
                $token->getIndex(),
                $token->getContent(),
                $token->getStartLine(),
                $token->getStartColumn(),
                $token->getEndLine(),
                $token->getEndColumn(),
                $token->getLevel(),
                $this
            );
        }

        $this->tokens = $newTokens;
    }

    /**
     * Class constructor. Loads tokens from a source that can be an instance of TokenToolkit\File\File,
     * a string or an array of tokens.
     *
     * @param string $source       Source code to extract tokens from
     * @param string $eolCharacter End of line character
     *
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
     * @param int $endIndex   End index for the extraction
     *
     * @return TokenToolkit\TokenStack The extracted token stack
     */
    public function extractTokenStack($startIndex, $endIndex)
    {
        $tokens = array_slice($this->tokens, $startIndex, $endIndex - $startIndex + 1);

        return new TokenStack($tokens);
    }

    /**
     * Returns first encountered token matching the criteria. If none is found then false is returned.
     *
     * @param int|array $type         Type or array of types the token must match.
     * @param int $direction          Direction of the search. It can be either TokenToolkit\Search\Query::BACKWARD
     *                                or TokenToolkit\Search\Query::FORWARD.
     * @param int $startIndex         Index of the token to start the search from
     * @param int $endIndex           Index of the token where to end the search
     * @param int|array $excludedType Type or array of types the token must not match.
     *
     * @return TokenToolkit\Token\TokenInterface|false A token if one is found, false otherwise
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

    /**
     * Returns next token matching the criteria. If none is found then false is returned.
     *
     * @param int|array $type         Type or array of types the token must match.
     * @param int $startIndex         Index of the token to start the search from
     * @param int $endIndex           Index of the token where to end the search
     * @param int|array $excludedType Type or array of types the token must not match.
     *
     * @return TokenToolkit\Token\TokenInterface|false A token if one is found, false otherwise
     */
    public function findNextTokenByType($type, $startIndex = null, $endIndex = null, $excludedType = null)
    {
        return $this->findFirstTokenByType($type, SearchQuery::FORWARD, $startIndex, $endIndex, $excludedType);
    }

    /**
     * Returns previous token matching the criteria. If none is found then false is returned.
     *
     * @param int|array $type         Type or array of types the token must match.
     * @param int $startIndex         Index of the token to start the search from
     * @param int $endIndex           Index of the token where to end the search
     * @param int|array $excludedType Type or array of types the token must not match.
     *
     * @return TokenToolkit\Token\TokenInterface|false A token if one is found, false otherwise
     */
    public function findPreviousTokenByType($type, $startIndex = null, $endIndex = null, $excludedType = null)
    {
        return $this->findFirstTokenByType($type, SearchQuery::BACKWARD, $startIndex, $endIndex, $excludedType);
    }

    /**
     * Returns the token ending the stack.
     *
     * @return TokenToolkit\Token\Token Token ending the stack
     */
    public function getEndToken()
    {
        $endTokenIndex = count($this->tokens) - 1;

        return $this->tokens[$endTokenIndex];
    }

    /**
     * Returns the file object associated with the stack if there is one.
     *
     * @return TokenToolkit\File\File|null File object for the stack if any, null otherwise
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Returns the token starting the stack.
     *
     * @return TokenToolkit\Token\Token Token starting the stack
     */
    public function getStartToken()
    {
        return $this->tokens[0];
    }


    /**
     * Returns token name from token code
     *
     * @param int $tokenCode Token code
     *
     * @see TokenToolkit\Tokenizer\Php::getTokenName()
     * @return string Token name
     */
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
     *
     *
     * @see \SeekableIterator::seek()
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
