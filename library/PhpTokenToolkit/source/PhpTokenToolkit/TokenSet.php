<?php
namespace PhpTokenToolkit;

use PhpTokenToolkit\Token\AbstractToken;
use PhpTokenToolkit\Tokenizer\Php as PhpTokenizer;

class TokenSet
{
    protected $tokenizer;

    protected $tokens = array();

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

    protected function processSourceString($source)
    {
        if (!is_string($source)) {
            throw new \InvalidArgumentException('Source must be a string');
        }

        $tokenIndex = 0;
        $tokens = $this->getTokenizer()->getTokens($source);
        foreach ($tokens as $token) {
            $tokenName    = $this->getTokenizer()->getTokenName($token[0]);
            $tokenClass   = $this->getTokenClass($tokenName);
            $tokenContent = $token[1];
            $tokenLine    = $token[2];

            $this->tokens[] = new $tokenClass(
                $tokenIndex,
                $tokenContent,
                $tokenLine,
                $this
            );
            $tokenIndex++;
        }
    }

    public function __construct($source)
    {
        $this->processSourceString($source);
    }

    public function dump()
    {
        $dump = '';
        foreach ($this->tokens as $token) {
            $dump .= $token->dump();
        }

        return $dump;
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