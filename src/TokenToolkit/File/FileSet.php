<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package TokenToolkit
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace TokenToolkit\File;

use TokenToolkit\File\Iterator\ExcludeFilterIterator;
use TokenToolkit\File\Iterator\FileIterator;
use TokenToolkit\File\Iterator\FileExtensionFilterIterator;
use TokenToolkit\Search\Query as SearchQuery;
use TokenToolkit\Search\Result\ResultSet;

/**
 * Set of PHP files representation
 *
 * @package TokenToolkit
 * @subpackage File
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class FileSet implements \IteratorAggregate
{
    private $allowedExtensions = array(
        '.php',
        '.php3',
        '.php4',
        '.php5',
    );

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

    public function addAllowedExtension($extension)
    {
        $this->allowedExtensions[] = $extension;

        return $this;
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

    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }

    public function search($searchPatterns, $direction = SearchQuery::FORWARD)
    {
        $resultSet = new ResultSet();

        foreach ($this as $file) {
            $resultSet->merge(
                $file->search($searchPatterns, $direction)
            );
        }

        return $resultSet;
    }

    public function setAllowedExtensions(array $extensions)
    {
        $this->allowedExtensions = $extensions;

        return $this;
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

        $iterator = new FileExtensionFilterIterator($iterator);
        $iterator->setAllowedExtensions($this->allowedExtensions);

        return $iterator;
    }
}
