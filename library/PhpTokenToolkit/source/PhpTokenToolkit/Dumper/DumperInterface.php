<?php
namespace PhpTokenToolkit\Dumper;

use PhpTokenToolkit\TokenStack;
use PhpTokenToolkit\Token\Php\AbstractPhpToken;

interface DumperInterface
{
    public function dump(TokenStack $tokenStack);

    public function dumpToken(AbstractPhpToken $token);
}