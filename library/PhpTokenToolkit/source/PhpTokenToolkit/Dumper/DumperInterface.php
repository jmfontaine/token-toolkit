<?php
namespace PhpTokenToolkit\Dumper;

use PhpTokenToolkit\TokenStack;
use PhpTokenToolkit\Token\TokenInterface;

interface DumperInterface
{
    public function dump(TokenStack $tokenStack);

    public function dumpToken(TokenInterface $token);
}