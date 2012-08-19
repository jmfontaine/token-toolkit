<?php
namespace TokenToolkit\Token\Php;

use TokenToolkit\TokenStack;
use TokenToolkit\Token\AbstractToken;

abstract class AbstractPhpToken extends AbstractToken
{
    protected $isCustom = false;
}
