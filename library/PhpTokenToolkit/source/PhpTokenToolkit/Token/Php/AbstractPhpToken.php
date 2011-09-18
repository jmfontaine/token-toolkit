<?php
namespace PhpTokenToolkit\Token\Php;

use PhpTokenToolkit\TokenStack;
use PhpTokenToolkit\Token\AbstractToken;

abstract class AbstractPhpToken extends AbstractToken
{
    protected $isCustom = false;
}