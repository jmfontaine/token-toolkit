<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package TokenToolkit
 * @subpackage Search
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace TokenToolkit\Search\Pattern;

use TokenToolkit\Search\Query as SearchQuery;
use TokenToolkit\Search\Pattern\PatternInterface as SearchPatternInterface;
use TokenToolkit\Token\TokenInterface;

// KLUDGE: We need to include the PHP tokenizer file to make sure that custom constants are defined.
// Otherwise it may not be loaded yet by the time this file is loaded depending on the order
// of the classes instanciations.
require_once 'TokenToolkit/Tokenizer/Php.php';

/**
 *
 *
 * @package TokenToolkit
 * @subpackage Search
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
abstract class AbstractPattern implements SearchPatternInterface
{
    protected $acceptAnyTokenType = false;

    protected $acceptedTokenTypes = array();

    protected $callback;

    protected $content;

    protected $contentIsRegex = false;

    protected $endIndex = null;

    protected $endLine = null;

    protected $excludedTokenTypes = array();

    protected $name;

    protected $startIndex = null;

    protected $startLine = null;

    public function getName()
    {
        return null === $this->name ? get_class($this) : $this->name;
    }

    protected function addAcceptedTokenType($tokenType)
    {
        if (T_ANY === $tokenType) {
            $this->acceptAnyTokenType = true;
        } elseif (!in_array($tokenType, $this->acceptedTokenTypes)) {
            $this->acceptedTokenTypes[] = $tokenType;
        }

        return $this;
    }

    protected function addExcludedTokenType($tokenType)
    {
        if (!in_array($tokenType, $this->excludedTokenTypes)) {
            $this->excludedTokenTypes[] = $tokenType;
        }

        return $this;
    }

    public function match(TokenInterface $token, $direction = SearchQuery::FORWARD)
    {
        if (null !== $this->startIndex) {
            if (($direction == SearchQuery::BACKWARD && $token->getIndex() > $this->startIndex) ||
                ($direction == SearchQuery::FORWARD && $token->getIndex() < $this->startIndex)) {
                return false;
            }
        }

        if (null !== $this->endIndex) {
            if (($direction == SearchQuery::BACKWARD && $token->getIndex() < $this->endIndex) ||
                ($direction == SearchQuery::FORWARD && $token->getIndex() > $this->endIndex)) {
                return false;
            }
        }

        if (null !== $this->startLine && $token->getStartLine() < $this->startLine) {
            return false;
        }

        if (null !== $this->endLine && $token->getEndLine() > $this->endLine) {
            return false;
        }

        if (null !== $this->content) {
            if ($this->contentIsRegex) {
                if (0 === preg_match($this->content, $token->getContent())) {
                    return false;
                }
            } elseif ($token->getContent() != $this->content) {
                return false;
            }
        }

        if (!$this->acceptAnyTokenType && !in_array($token->getType(), $this->acceptedTokenTypes)) {
            return false;
        }

        if (in_array($token->getType(), $this->excludedTokenTypes)) {
            return false;
        }

        if (null !== $this->callback) {
            return call_user_func($this->callback, $token);
        }

        return true;
    }

    public function setCallback($callback)
    {
        if ('Closure' !== get_class($callback)) {
            throw new \InvalidArgumentException("Callback must be a closure");
        }

        $this->callback = $callback;

        return $this;
    }

    public function setContent($content, $isRegex = false)
    {
        if (!is_string($content)) {
            throw new \InvalidArgumentException("Invalid content ($content)");
        }

        if (!is_bool($isRegex)) {
            throw new \InvalidArgumentException("Invalid boolean value ($isRegex)");
        }

        $this->content        = $content;
        $this->contentIsRegex = $isRegex;

        return $this;
    }

    public function setEndIndex($index)
    {
        if (!is_int($index) || 0 > $index) {
            throw new \InvalidArgumentException("Invalid end index ($index)");
        }

        $this->endIndex = $index;

        return $this;
    }

    public function setEndLine($line)
    {
        if (!is_int($line) || 1 > $line) {
            throw new \InvalidArgumentException("Invalid end line ($line)");
        }

        $this->endLine = $line;

        return $this;
    }

    public function setName($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException("Invalid name ($name)");
        }

        $this->name = $name;

        return $this;
    }

    public function setStartIndex($index)
    {
        if (!is_int($index) || 0 > $index) {
            throw new \InvalidArgumentException("Invalid start index ($index)");
        }

        $this->startIndex = $index;

        return $this;
    }

    public function setStartLine($line)
    {
        if (!is_int($line) || 1 > $line) {
            throw new \InvalidArgumentException("Invalid start line ($line)");
        }

        $this->startLine = $line;

        return $this;
    }
}
