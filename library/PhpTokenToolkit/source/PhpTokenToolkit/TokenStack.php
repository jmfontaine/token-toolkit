<?php
namespace PhpTokenToolkit;

use PhpTokenToolkit\Token\AbstractToken;
use PhpTokenToolkit\Tokenizer\Php as PhpTokenizer;

class TokenStack implements \Iterator
{
    protected $iteratorCursor = 0;

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
}