<?php
namespace PhpTokenToolkit\Token\Php;

use PhpTokenToolkit\Token\AbstractTokenWithScope;

abstract class AbstractPhpTokenWithScope extends AbstractTokenWithScope
{
    protected $isCustom = false;
}