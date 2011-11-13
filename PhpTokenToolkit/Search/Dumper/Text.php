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
namespace PhpTokenToolkit\Search\Dumper;

use PhpTokenToolkit\Search\Dumper\DumperInterface;
use PhpTokenToolkit\Search\Result\ResultSet;

/**
 * PHP resultset dumper
 *
 * @package PHP Token Toolkit
 * @subpackage Search
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class Text implements DumperInterface
{
    public function dump(ResultSet $resultSet)
    {
        $boldDelimiter   = str_repeat('=', 80) . "\n";
        $normalDelimiter = str_repeat('-', 80) . "\n";

        $numberOfResults = count($resultSet);

        $template = <<<EOT
# %d
Search pattern: %s
File          : %s
Token
    Name        : %s
    Type        : %d
    Start line  : %d
    Start column: %d
    End line    : %d
    End column  : %d
    Content     : %s

EOT;

        $text  = $boldDelimiter;
        $text .= "$numberOfResults results found\n";
        $text .= $boldDelimiter;

        foreach ($resultSet as $resultIndex => $result) {
            $token = $result->getToken();
            $text .= sprintf(
                $template,
                $resultIndex + 1,
                $result->getSearchPattern()->getName(),
                $token->getTokenStack()->getFile()->getPath(),
                $token->getName(),
                $token->getType(),
                $token->getStartLine(),
                $token->getStartColumn(),
                $token->getEndLine(),
                $token->getEndColumn(),
                $token->getContent()
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
}