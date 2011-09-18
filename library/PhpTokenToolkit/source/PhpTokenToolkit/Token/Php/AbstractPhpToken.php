<?php
namespace PhpTokenToolkit\Token\Php;

use PhpTokenToolkit\TokenStack;

abstract class AbstractPhpToken
{
    protected $content;

    protected $endColumn;

    protected $endLine;

    protected $index;

    protected $isCustom = false;

    protected $name = 'THIS MUST BE DEFINED IN CONCRETE CLASSES';

    protected $startColumn;

    protected $startLine;

    protected $tokenStack;

    public function __construct($index, $content, $startLine, $startColumn,
        $endLine, $endColumn, TokenStack $tokenStack)
    {
        $this->index       = $index;
        $this->content     = $content;
        $this->startLine   = $startLine;
        $this->startColumn = $startColumn;
        $this->endLine     = $endLine;
        $this->endColumn   = $endColumn;
        $this->tokenStack  = $tokenStack;
    }

    public function getType()
    {
        return constant($this->name);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getEndColumn()
    {
        return $this->endColumn;
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

    public function getStartColumn()
    {
        return $this->startColumn;
    }

    public function getStartLine()
    {
        return $this->startLine;
    }

    public function getTokenStack()
    {
        return $this->tokenStack;
    }

    public function isCustom()
    {
        return $this->isCustom;
    }
}