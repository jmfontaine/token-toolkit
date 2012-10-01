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
 * File iterator
 *
 * @package TokenToolkit
 * @subpackage File
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class FileIterator extends \ArrayIterator
{
    public function current()
    {
        return new File(parent::current());
    }
}
