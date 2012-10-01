<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package TokenToolkit
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace TokenToolkit\File\Iterator;

/**
 * Iterator that filters files on their extension.
 *
 * @package TokenToolkit
 * @subpackage File
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class FileExtensionFilterIterator extends \FilterIterator
{
    private $allowedExtensions = array();

    public function addAllowedExtension($extension)
    {
        $this->allowedExtensions[] = $extension;

        return $this;
    }

    public function accept()
    {
        $file = $this->getInnerIterator()->current();
        return in_array($file->getExtension(), $this->allowedExtensions);
    }

    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }

    public function setAllowedExtensions(array $extensions)
    {
        $this->allowedExtensions = $extensions;

        return $this;
    }
}
