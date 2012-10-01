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
 * JSON dumper
 *
 * @package TokenToolkit
 * @subpackage Dumper
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class Json extends AbstractDumper
{
    public function dumpFile(File $file)
    {
        return json_encode($file->toArray());
    }

    public function dumpFileSet(FileSet $fileSet)
    {
        $data = array();

        foreach ($fileSet as $file) {
            $data[] = $file->toArray();
        }

        return json_encode($data);
    }

    public function dumpSearchResult(Result $result)
    {
        return json_encode($result->toArray());
    }

    public function dumpSearchResultSet(ResultSet $resultSet)
    {
        return json_encode($resultSet->toArray());
    }

    public function dumpToken(TokenInterface $token)
    {
        return json_encode($token->toArray());
    }

    public function dumpTokenStack(TokenStack $tokenStack)
    {
        $data = array();

        foreach ($tokenStack as $token) {
            $data[] = $token->toArray();
        }

        return json_encode($data);
    }
}
