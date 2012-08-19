<?php
/**
 * Copyright (c) 2011-2012, Jean-Marc Fontaine
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
 * @package TokenToolkit
 * @subpackage Dumper
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
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
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
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
