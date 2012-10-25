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

use TokenToolkit\Search\Result\Result;
use TokenToolkit\Search\Result\ResultSet;
use TokenToolkit\Search\Pattern\PatternInterface as SearchPatternInterface;
use TokenToolkit\Token\TokenInterface;
use TokenToolkit\TokenStack;

/**
 * Query for PHP token search
 *
 * @package TokenToolkit
 * @subpackage Search
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class Query
{
    const BACKWARD = -1;
    const FORWARD  = 1;

    protected $direction = self::FORWARD;

    protected $limit;

    protected $searchPatterns = array();

    protected $tokenStack;

    public function __construct(TokenStack $tokenStack, $searchPatterns = array())
    {
        if (is_object($searchPatterns)) {
            $searchPatterns = array($searchPatterns);
        }

        $this->searchPatterns = $searchPatterns;
        $this->tokenStack     = $tokenStack;
    }

    public function addSearchPattern(SearchPatternInterface $searchPattern)
    {
        $this->searchPatterns[] = $searchPattern;

        return $this;
    }

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

    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function setTokenStack(TokenStack $tokenStack)
    {
        $this->tokenStack = $tokenStack;

        return $this;
    }
}
