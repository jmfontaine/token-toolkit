<?php
namespace TokenToolkit\Dumper;

use TokenToolkit\TokenStack;
use TokenToolkit\Token\TokenInterface;

interface DumperInterface
{
    public function dump(TokenStack $tokenStack);

    public function dumpToken(TokenInterface $token);
}
