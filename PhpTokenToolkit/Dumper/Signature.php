<?php
/**
 * Copyright (c) 2011, Jean-Marc Fontaine
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
 * @package PHP Token Toolkit
 * @subpackage Dumper
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace PhpTokenToolkit\Dumper;

use PhpTokenToolkit\File\File;
use PhpTokenToolkit\File\FileSet;
use PhpTokenToolkit\Search\Result\Result;
use PhpTokenToolkit\Search\Result\ResultSet;
use PhpTokenToolkit\TokenStack;
use PhpTokenToolkit\Token\TokenInterface;

/**
 * Signature dumper
 *
 * This dumper creates a visual signature PHP code using some of its key elements.
 * This is based on an article by Ward Cunningham (http://c2.com/doc/SignatureSurvey/).
 *
 * @package PHP Token Toolkit
 * @subpackage Dumper
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class Signature extends AbstractDumper
{
    public function dumpFile(File $file)
    {
        return sprintf(
            "%s: %s\n",
            $file->getPath(),
            $this->dumpTokenStack($file->getTokenStack())
        );
    }

    public function dumpFileSet(FileSet $fileSet)
    {
        $data = '';

        foreach ($fileSet as $file) {
            $data .= $this->dumpFile($file);
        }

        return $data;
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
        throw new \Exception('This dumper is not meant to dump individual tokens.');
    }

    public function dumpTokenStack(TokenStack $tokenStack)
    {
        $allowedTokenTypes = array(
            T_OPEN_CURLY_BRACKET,
            T_CLOSE_CURLY_BRACKET,
            T_SEMICOLON,
        );
        $result = '';
        foreach ($tokenStack as $token) {
            if (in_array($token->getType(), $allowedTokenTypes)) {
                $result .= $token->getContent();
            }
        }

        return $result;
    }
}