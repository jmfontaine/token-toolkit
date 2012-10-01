<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package TokenToolkit
 * @subpackage Dumper
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace TokenToolkit\Dumper;

use TokenToolkit\Token\TokenInterface;

/**
 * Interface for data dumpers
 *
 * @package TokenToolkit
 * @subpackage Dumper
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
abstract class AbstractDumper implements DumperInterface
{
    public function dump($object)
    {
        switch (get_class($object)) {
            case 'TokenToolkit\File\File':
                $data = $this->dumpFile($object);
                break;
            case 'TokenToolkit\File\FileSet':
                $data = $this->dumpFileSet($object);
                break;
            case 'TokenToolkit\Search\Result\Result':
                $data = $this->dumpSearchResult($object);
                break;
            case 'TokenToolkit\Search\Result\ResultSet':
                $data = $this->dumpSearchResultSet($object);
                break;
            case 'TokenToolkit\TokenStack':
                $data = $this->dumpTokenStack($object);
                break;
            default:
                if ($object instanceof TokenInterface) {
                    $data = $this->dumpToken($object);
                } else {
                    throw new \InvalidArgumentException('The argument is not an instance of the supported classes.');
                }
        }

        return $data;
    }
}
