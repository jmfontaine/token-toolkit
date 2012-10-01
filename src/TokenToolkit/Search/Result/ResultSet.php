<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package TokenToolkit
 * @subpackage Search
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace TokenToolkit\Search\Result;

/**
 * Set of results for a token search
 *
 * @package TokenToolkit
 * @subpackage Search
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class ResultSet implements \ArrayAccess, \Countable, \SeekableIterator
{
    protected $iteratorCursor = 0;

    protected $results = array();

    public function add(Result $result)
    {
        $this->results[] = $result;
    }

    public function merge(ResultSet $resultSet)
    {
        $results = array_merge($this->getResults(), $resultSet->getResults());
        $this->setResults($results);

        return $this;
    }

    public function toArray()
    {
        $data = array();
        foreach ($this->results as $result) {
            $data[] = $result->toArray();
        }

        return $data;
    }

    /*
     * Iterator methods
     */
    public function current()
    {
        return $this->results[$this->iteratorCursor];
    }

    public function getResults()
    {
        return $this->results;
    }

    public function key()
    {
        return $this->iteratorCursor;
    }

    public function next()
    {
        $this->iteratorCursor++;
    }

    public function rewind()
    {
        $this->iteratorCursor = 0;
    }

    public function setResults(array $results)
    {
        $this->results = array();

        foreach ($results as $result) {
            $this->add($result);
        }

        return $this;
    }

    public function valid()
    {
        return array_key_exists($this->iteratorCursor, $this->results);
    }

    /*
     * ArrayAccess interface methods
     */

    public function offsetExists($offset)
    {
        return isset($this->results[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->results[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->results[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->results[$offset]);
    }

    /*
     * SeekableIterator interface method
     */

    /**
     * (non-PHPdoc)
     * @see SeekableIterator::seek()
     */
    public function seek($position) {
      $this->iteratorCursor = $position;

      if (!$this->valid()) {
          throw new \OutOfBoundsException("Invalid seek position ($position)");
      }

      return $this;
    }

    /*
     * Countable interface methods
     */
    public function count()
    {
        return count($this->results);
    }
}
