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
 * @package PHP Token Toolkit
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace PhpTokenToolkit\File;

use PhpTokenToolkit\File\Iterator\ExcludeFilterIterator;
use PhpTokenToolkit\File\Iterator\FileIterator;
use PhpTokenToolkit\Search\Query as SearchQuery;
use PhpTokenToolkit\Search\Result\ResultSet;

/**
 * Set of PHP files representation
 *
 * @package PHP Token Toolkit
 * @subpackage File
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class FileSet implements \IteratorAggregate
{
    protected $directories = array();

    protected $exclude = array();

    protected $files = array();

    protected function getDirectoryIterator($directory)
    {
        $flags = \RecursiveDirectoryIterator::SKIP_DOTS;

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, $flags),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $files = array();
        foreach($iterator as $item) {
            if ($item->isFile()) {
                $files[] = $item->getPathname();
            }
        }

        return new FileIterator($files);
    }

    public function __construct($path = null)
    {
        if (null !== $path) {
            $this->addPath($path);
        }
    }

    public function addPath($path)
    {
        foreach ((array) $path as $item) {
            if (is_file($item)) {
                $this->files[] = $item;
            } elseif (is_dir($item)) {
                $this->directories[] = $item;
            }
        }

        return $this;
    }

    public function exclude($pattern)
    {
        $this->exclude[] = $pattern;

        return $this;
    }

    public function search(array $searchPatterns, $direction = SearchQuery::FORWARD)
    {
        $resultSet = new ResultSet();

        foreach ($this as $file) {
            $resultSet->merge(
                $file->search($searchPatterns, $direction)
            );
        }

        return $resultSet;
    }

    public function setPath($path)
    {
        $this->directories = array();
        $this->files       = array();

        $this->addPath($path);

        return $this;
    }

    /*
     * IteratorAggregate method
     */
    public function getIterator()
    {
        $iterator = new \AppendIterator();
        foreach ($this->directories as $directory) {
            $iterator->append($this->getDirectoryIterator($directory));
        }

        $iterator->append(new FileIterator($this->files));

        if (!empty($this->exclude)) {
            $iterator = new ExcludeFilterIterator($iterator, $this->exclude);
        }

        return $iterator;
    }
}