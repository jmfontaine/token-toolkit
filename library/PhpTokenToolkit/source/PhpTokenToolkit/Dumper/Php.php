<?php
namespace PhpTokenToolkit\Dumper;

use PhpTokenToolkit\TokenStack;
use PhpTokenToolkit\Token\Php\AbstractPhpToken;

class Php implements DumperInterface
{
    public function dump(TokenStack $tokenStack)
    {
        $result = '';
        foreach ($tokenStack->getTokens() as $token) {
            $result .= $token->getContent();
        }

        return $result;
    }

    public function dumpToken(AbstractPhpToken $token)
    {
        return $token->getContent();
    }
}