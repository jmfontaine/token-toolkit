<?php
namespace PhpTokenToolkit\Dumper;

use PhpTokenToolkit\TokenStack;
use PhpTokenToolkit\Token\TokenInterface;

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

    public function dumpToken(TokenInterface $token)
    {
        return $token->getContent();
    }
}