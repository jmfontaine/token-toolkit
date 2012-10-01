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
 * Text dumper
 *
 * This dumper displays informations about the tokens. It is mainly
 * intended for debugging.
 *
 * @package TokenToolkit
 * @subpackage Dumper
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class Text extends AbstractDumper
{
    public function dumpFile(File $file)
    {
        $data  = $file->getPath() . "\n";
        $data .= $this->dumpTokenStack($file->getTokenStack());

        return $data;
    }

    public function dumpFileSet(FileSet $fileSet)
    {
        $delimiter = str_repeat('-', 80) . "\n";
        $data      = '';

        foreach ($fileSet as $index => $file) {
            $data .= $this->dumpFile($file) . "\n$delimiter";
        }

        // Remove last delimiter
        $data = substr($data, 0, -81);

        return $data;
    }

    public function dumpSearchResult(Result $result)
    {
        $template = <<<EOT
Search pattern: %s
File          : %s
Token         : %s

EOT;

        $token    = $result->getToken();
        $file     = $token->getTokenStack()->getFile();
        $filePath = $file instanceof File ? $file->getPath() : '-';
        $data     = '';

        return sprintf(
                $template,
                $result->getSearchPattern()->getName(),
                $filePath,
                $this->dumpToken($token)
        );
    }

    public function dumpSearchResultSet(ResultSet $resultSet)
    {
        $boldDelimiter   = str_repeat('=', 80) . "\n";
        $normalDelimiter = str_repeat('-', 80) . "\n";

        $numberOfResults = count($resultSet);

        $text  = $boldDelimiter;
        $text .= "$numberOfResults results found\n";
        $text .= $boldDelimiter;

        foreach ($resultSet as $resultIndex => $result) {
            $text .= sprintf(
                "#%d\n%s",
                $resultIndex + 1,
                $this->dumpSearchResult($result)
            );

            if ($resultIndex < $numberOfResults - 1) {
                $text .= $normalDelimiter;
            }
        }

        if (0 < $numberOfResults) {
            $text .= $boldDelimiter;
        }

        return $text;
    }

    public function dumpToken(TokenInterface $token)
    {
        return sprintf(
            '#%d - %s "%s" (%d:%d -> %d:%d)' . PHP_EOL,
            $token->getIndex(),
            $token->getName(),
            $token->getContent(),
            $token->getStartLine(),
            $token->getStartColumn(),
            $token->getEndLine(),
            $token->getEndColumn()
        );
    }

    public function dumpTokenStack(TokenStack $tokenStack)
    {
        $result = '';
        foreach ($tokenStack as $token) {
            $result .= $this->dumpToken($token);
        }

        return $result;
    }
}
