<?php
namespace PhpTokenToolkit\Dumper;

use PhpTokenToolkit\TokenSet;
use PhpTokenToolkit\Token\Php\AbstractPhpToken;

interface DumperInterface
{
    public function dump(TokenSet $tokenSet);

    public function dumpToken(AbstractPhpToken $token);
}