<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package TokenToolkit
 * @subpackage Search
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace TokenToolkit\Search;

use TokenToolkit\Iterator\TypeFilterIterator;
use TokenToolkit\Iterator\ContentFilterIterator;
use TokenToolkit\Search\Result\Result;
use TokenToolkit\Search\Result\ResultSet;
use TokenToolkit\TokenStack;

// KLUDGE: We need to include the PHP tokenizer file to make sure that custom constants are defined.
// Otherwise it may not be loaded yet by the time this file is loaded depending on the order
// of the classes instanciations.
require_once __DIR__.'/../Tokenizer/Php.php';

/**
 * Query for PHP token search
 *
 * @package TokenToolkit
 * @subpackage Search
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class Query implements \IteratorAggregate
{
    const BACKWARD = -1;
    const FORWARD  = 1;

    protected $allowedTypes = array();

    protected $allowedContents = array();

    protected $direction = self::FORWARD;

    protected $forbiddenTypes = array();

    protected $forbiddenContents = array();

    protected $limit;

    protected $tokenStack;

    public function getDirection()
    {
        return $this->direction;
    }

    public function getTokenStack()
    {
        return $this->tokenStack;
    }

    public function search()
    {
        $resultSet = new ResultSet();

        $tokensCount  = count($this->getTokenStack());

        // If the stack is empty return now
        if (0 === $tokensCount) {
            return $resultSet;
        }

        if (self::FORWARD === $this->direction) {
            $startIndex = 0;
            $endIndex   = $tokensCount - 1;
        } else {
            $startIndex = $tokensCount - 1;
            $endIndex   = 0;
        }

        $resultsCount = 0;
        for ($i = $startIndex; /* Omitted on purpose */; /* Omitted on purpose */) {
            $token = $this->tokenStack[$i];

            foreach ($this->searchPatterns as $pattern) {
                if ($pattern->match($token, $this->direction)) {
                    $resultSet->add(new Result($token, $pattern));
                    $resultsCount++;

                    // Stop if we have enough results
                    if (null !== $this->limit && $resultsCount >= $this->limit) {
                        break 2;
                    }
                }
            }

            // Handle end of loop and incrementation/decrementation
            if (self::FORWARD === $this->direction) {
                if ($i >= $endIndex) {
                    break;
                } else {
                    $i++;
                }
            } else {
                if ($i <= $endIndex) {
                    break;
                } else {
                    $i--;
                }
            }
        }

        return $resultSet;
    }

    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function searchIn(TokenStack $tokenStack)
    {
        $this->tokenStack = $tokenStack;

        return $this;
    }

    public function lookBackward()
    {
        $this->direction = self::BACKWARD;

        return $this;
    }

    public function lookForward()
    {
        $this->direction = self::FORWARD;

        return $this;
    }

    public function is($type)
    {
        $this->allowedTypes[] = $type;

        return $this;
    }

    public function isNot($type)
    {
        $this->forbiddenTypes[] = $type;

        return $this;
    }

    public function getIterator()
    {
        $iterator = $this->getTokenStack();

        $iterator = new TypeFilterIterator($iterator, $this->allowedTypes, $this->forbiddenTypes);

        $iterator = new ContentFilterIterator($iterator, $this->allowedContents, $this->forbiddenContents);

        return $iterator;
    }

    public function contentIs($content)
    {
        $this->allowedContents[] = $content;

        return $this;
    }

    public function contentIsNot($content)
    {
        $this->forbiddenContents[] = $content;

        return $this;
    }
}
