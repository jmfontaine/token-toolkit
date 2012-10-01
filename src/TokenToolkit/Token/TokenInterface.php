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
 * Interface for tokens
 *
 * @package    TokenToolkit
 * @subpackage Token
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
interface TokenInterface
{
    public function __construct($index, $content, $startLine, $startColumn,
        $endLine, $endColumn, $level, TokenStack $tokenStack);

    public function getType();

    public function getContent();

    public function getEndColumn();

    public function getEndLine();

    public function getIndex();

    public function getLevel();

    public function getName();

    public function getStartColumn();

    public function getStartLine();

    public function getTokenStack();

    public function toArray();
}
