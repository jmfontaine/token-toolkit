<?php
namespace PhpTokenToolkit\Token\Php;

use PhpTokenToolkit\TokenSet;

abstract class AbstractPhpToken
{
    protected $content;

    protected $endLine;

    protected $index;

    protected $isCustom = false;

    protected $name = 'THIS MUST BE DEFINED IN CONCRETE CLASSES';

    protected $startLine;

    protected $tokenSet;

    public function __construct($index, $content, $startLine, $endLine,
        TokenSet $tokenSet)
    {
        $this->index     = $index;
        $this->content   = $content;
        $this->startLine = $startLine;
        $this->endLine   = $endLine;
        $this->tokenSet  = $tokenSet;
    }

    public function dump()
    {
        return sprintf(
            '%d: %s "%s" (%d-%d)' . PHP_EOL,
            $this->index,
            $this->name,
            $this->content,
            $this->startLine,
            $this->endLine
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

    public function getEndLine()
    {
        return $this->endLine;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getStartLine()
    {
        return $this->startLine;
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