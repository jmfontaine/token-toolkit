<?php
namespace PhpTokenToolkit\Token\Custom;

use PhpTokenToolkit\Token\Php\AbstractPhpToken;

abstract class AbstractCustomToken extends AbstractPhpToken
{
    protected $isCustom = true;
}