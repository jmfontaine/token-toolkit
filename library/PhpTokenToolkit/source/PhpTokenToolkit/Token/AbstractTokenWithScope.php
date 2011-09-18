<?php
namespace PhpTokenToolkit\Token;

abstract class AbstractTokenWithScope extends AbstractToken
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