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

use TokenToolkit\File\File;
use TokenToolkit\File\FileSet;
use TokenToolkit\Search\Result\Result;
use TokenToolkit\Search\Result\ResultSet;
use TokenToolkit\TokenStack;
use TokenToolkit\Token\TokenInterface;

/**
 * Interface for data dumpers
 *
 * @package TokenToolkit
 * @subpackage Dumper
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
interface DumperInterface
{
    public function dump($data);

    public function dumpFile(File $file);

    public function dumpFileSet(FileSet $fileSet);

    public function dumpSearchResult(Result $result);

    public function dumpSearchResultSet(ResultSet $resultSet);

    public function dumpToken(TokenInterface $token);

    public function dumpTokenStack(TokenStack $tokenStack);
}
