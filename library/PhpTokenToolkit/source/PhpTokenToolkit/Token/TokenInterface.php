<?php
namespace PhpTokenToolkit\Token;

use PhpTokenToolkit\TokenStack;

interface TokenInterface
{
    public function __construct($index, $content, $startLine, $startColumn,
        $endLine, $endColumn, TokenStack $tokenStack);

    public function getType();

    public function getContent();

    public function getEndColumn();

    public function getEndLine();

    public function getIndex();

    public function getName();

    public function getStartColumn();

    public function getStartLine();

    public function getTokenStack();

    public function isCustom();
}