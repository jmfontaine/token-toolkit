<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package TokenToolkit
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace TokenToolkit\File\Iterator;

use TokenToolkit\File\File;

/**
 * Exclude filter iterator
 *
 * @package TokenToolkit
 * @subpackage File
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class ExcludeFilterIterator extends \FilterIterator
{
    protected $patterns = array();

    public function __construct(\Iterator $iterator, array $patterns)
    {
        foreach ($patterns as $pattern) {
            $pattern = '/' . preg_quote($pattern, '/') . '/';
            $pattern = str_replace('\*', '.*?', $pattern);
            $this->patterns[] = $pattern;
        }

        parent::__construct($iterator);
    }

    public function accept()
    {
        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $this->current()->getFilename())) {
                return false;
            }
        }

        return true;
    }
}
