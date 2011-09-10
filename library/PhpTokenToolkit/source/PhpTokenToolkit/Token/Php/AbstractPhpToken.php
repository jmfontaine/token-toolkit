<?php
namespace PhpTokenToolkit\Token\Php;

use PhpTokenToolkit\TokenSet;

abstract class AbstractPhpToken
{
    protected $content;

    protected $index;

    protected $isCustom = false;

    protected $line;

    protected $name = 'THIS MUST BE DEFINED IN CONCRETE CLASSES';

    protected $tokenSet;

    public function __construct($index, $content, $line, TokenSet $tokenSet)
    {
        $this->index    = $index;
        $this->content  = $content;
        $this->line     = $line;
        $this->tokenSet = $tokenSet;
    }

    public function dump()
    {
        return sprintf(
            '%d: %s "%s" (%d)' . PHP_EOL,
            $this->index,
            $this->name,
            $this->content,
            $this->line
        );
    }

    public function getCode()
    {
        return constant($this->name);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function getLine()
    {
        return $this->line;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTokenSet()
    {
        return $this->tokenSet;
    }

    public function isCustom()
    {
        return $this->isCustom;
    }
}