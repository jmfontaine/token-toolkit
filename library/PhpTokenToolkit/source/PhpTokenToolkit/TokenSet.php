<?php
namespace PhpTokenToolkit;

use PhpTokenToolkit\Token\AbstractToken;
use PhpTokenToolkit\Tokenizer\Php as PhpTokenizer;

class TokenSet
{
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

    protected function getTokenClass($tokenName)
    {
        if ($this->getTokenizer()->isCustom(constant($tokenName))) {
            $tokenType = 'Custom';
        } else {
            $tokenType = 'Php';
        }

        $tokenName = substr($tokenName, 2);
        $tokenName = strtolower($tokenName);

        $result = "PhpTokenToolkit\Token\\$tokenType\\";
        foreach (explode('_', $tokenName) as $word) {
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

        $tokenIndex = 0;
        $tokens = $this->getTokenizer()->getTokens($source, $eolCharacter);
        foreach ($tokens as $token) {
            $tokenName        = $this->getTokenizer()->getTokenName($token[0]);
            $tokenClass       = $this->getTokenClass($tokenName);
            $tokenContent     = $token[1];
            $tokenStartLine   = $token[2];
            $tokenStartColumn = $token[3];
            $tokenEndLine     = $token[4];
            $tokenEndColumn   = $token[5];

            $this->tokens[] = new $tokenClass(
                $tokenIndex,
                $tokenContent,
                $tokenStartLine,
                $tokenStartColumn,
                $tokenEndLine,
                $tokenEndColumn,
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
}