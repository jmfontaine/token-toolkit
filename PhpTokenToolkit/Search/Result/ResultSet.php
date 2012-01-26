<?php
/**
 * Copyright (c) 2011-2012, Jean-Marc Fontaine
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
 * @package PhpTokenToolkit
 * @subpackage Search
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace PhpTokenToolkit\Search\Result;

/**
 * Set of results for a token search
 *
 * @package PhpTokenToolkit
 * @subpackage Search
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
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
