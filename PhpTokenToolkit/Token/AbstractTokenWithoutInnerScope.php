<?php
/**
 * Copyright (c) 2011, Jean-Marc Fontaine
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name PHP Token Toolkit nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL Jean-Marc Fontaine BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    PhpTokenToolkit
 * @subpackage Token
 * @author     Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright  2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License */
namespace PhpTokenToolkit\Token;

use PhpTokenToolkit\TokenStack;

/**
 * Abstract class for tokens
 *
 * This class is the base for every token classes.
 *
 * @package    PhpTokenToolkit
 * @subpackage Token
 * @author     Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright  2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License */
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

    public function __construct($index, $content, $startLine, $startColumn,
        $endLine, $endColumn, $level, TokenStack $tokenStack)
    {
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