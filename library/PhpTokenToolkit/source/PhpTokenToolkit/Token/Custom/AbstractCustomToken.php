<?php
namespace PhpTokenToolkit\Token\Custom;

use PhpTokenToolkit\Token\AbstractToken;

abstract class AbstractCustomToken extends AbstractToken
{
    protected $isCustom = true;
}