<?php
namespace PhpTokenToolkit\Token\Php;

abstract class AbstractPhpTokenWithScope extends AbstractPhpToken implements Iterator
{
    protected $endToken;

    public function getEndToken()
    {
        if (null === $this->endToken) {
            $tokens = $this->getTokenStack()->getTokens();

            // TODO: Complete this
        }

        return $this->endToken;
    }
}