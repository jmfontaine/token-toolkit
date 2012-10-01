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
 * PHP dumper
 *
 * @package TokenToolkit
 * @subpackage Dumper
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class Php extends AbstractDumper
{
    public function dumpFile(File $file)
    {
        return $this->dumpTokenStack($file->getTokenStack());
    }

    public function dumpFileSet(FileSet $fileSet)
    {
        throw new \Exception('This dumper is not meant to file sets.');
    }

    public function dumpSearchResult(Result $result)
    {
        throw new \Exception('This dumper is not meant to dump search results.');
    }

    public function dumpSearchResultSet(ResultSet $resultSet)
    {
        throw new \Exception('This dumper is not meant to dump search results.');
    }

    public function dumpToken(TokenInterface $token)
    {
        return $token->getContent();
    }

    public function dumpTokenstack(TokenStack $tokenStack)
    {
        $result = '';
        foreach ($tokenStack as $token) {
            $result .= $token->getContent();
        }

        return $result;
    }
}
