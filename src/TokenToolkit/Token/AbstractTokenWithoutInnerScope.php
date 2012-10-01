<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package    TokenToolkit
 * @subpackage Token
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace TokenToolkit\Token;

use TokenToolkit\TokenStack;

/**
 * Abstract class for tokens
 *
 * This class is the base for every token classes.
 *
 * @package    TokenToolkit
 * @subpackage Token
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
abstract class AbstractTokenWithoutInnerScope implements TokenInterface
{
    protected $content;

    protected $endColumn;

    protected $endLine;

    protected $index;

    protected $level;

    protected $name = 'THIS MUST BE DEFINED IN CONCRETE CLASSES';

    protected $startColumn;

    protected $startLine;

    protected $tokenStack;

    public function __construct(
        $index,
        $content,
        $startLine,
        $startColumn,
        $endLine,
        $endColumn,
        $level,
        TokenStack $tokenStack
    ) {
        $this->index       = $index;
        $this->content     = $content;
        $this->startLine   = $startLine;
        $this->startColumn = $startColumn;
        $this->endLine     = $endLine;
        $this->endColumn   = $endColumn;
        $this->level       = $level;
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

    public function getLevel()
    {
        return $this->level;
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

    public function toArray()
    {
        return array(
            'startLine'   => $this->getStartLine(),
            'startColumn' => $this->getStartColumn(),
            'endLine'     => $this->getEndLine(),
            'endColumn'   => $this->getEndColumn(),
            'content'     => $this->getContent(),
            'index'       => $this->getIndex(),
            'type'        => $this->getType(),
            'name'        => $this->getName(),
        );
    }
}
