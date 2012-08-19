<?php
namespace TokenToolkit\Dumper;

use TokenToolkit\TokenStack;
use TokenToolkit\Token\TokenInterface;

class Php implements DumperInterface
{
    public function dump(TokenStack $tokenStack)
    {
        $result = '';
        foreach ($tokenStack as $token) {
            $result .= $token->getContent();
        }

        return $result;
    }

    public function dumpToken(TokenInterface $token)
    {
        return $token->getContent();
    }
}
