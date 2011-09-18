<?php
namespace PhpTokenToolkit\Dumper;

use PhpTokenToolkit\TokenStack;
use PhpTokenToolkit\Token\TokenInterface;

class Text implements DumperInterface
{
    public function dump(TokenStack $tokenStack)
    {
        $result = '';
        foreach ($tokenStack as $token) {
            $result .= $this->dumpToken($token);
        }

        return $result;
    }

    public function dumpToken(TokenInterface $token)
    {
        return sprintf(
            '%d: %s "%s" (%d:%d -> %d:%d)' . PHP_EOL,
            $token->getIndex(),
            $token->getName(),
            $token->getContent(),
            $token->getStartLine(),
            $token->getStartColumn(),
            $token->getEndLine(),
            $token->getEndColumn()
        );
    }
}