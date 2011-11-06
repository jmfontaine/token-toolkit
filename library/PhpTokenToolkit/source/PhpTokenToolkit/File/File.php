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
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace PhpTokenToolkit\File;

use PhpTokenToolkit\Search\Query as SearchQuery;
use PhpTokenToolkit\TokenStack;

/**
 * PHP file representation
 *
 * @package PHP Token Toolkit
 * @subpackage File
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class File
{
    protected $eolCharacter;

    protected $filename;

    protected $tokenStack;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getEolCharacter()
    {
        if (null === $this->eolCharacter) {
            $handle = fopen($this->filename, 'r');
            if (false === $handle) {
                throw new \InvalidArgumentException(
                	"File $this->filename is not readable"
            	);
            }

            $firstLine = fgets($handle);
            fclose($handle);

            $this->eolCharacter = substr($firstLine, -1);
            if ("\n" === $this->eolCharacter) {
                $secondLastCharacter = substr($firstLine, -2, 1);
                if ("\r" === $secondLastCharacter) {
                    $this->eolCharacter = "\r\n";
                }
            } elseif ("\r" !== $this->eolCharacter) {
                $this->eolCharacter = "\n";
            }
        }

        return $this->eolCharacter;
    }

    public function getSource()
    {
        $source = file_get_contents($this->filename);

        return $source;
    }

    public function getTokenStack()
    {
        if (null === $this->tokenStack) {
            $this->tokenStack = new TokenStack(
                $this->getSource(),
                $this->getEolCharacter()
            );
        }

        return $this->tokenStack;
    }

    public function search(array $searchPatterns)
    {
        $searchQuery = new SearchQuery($this->getTokenStack(), $searchPatterns);

        return $searchQuery->search();
    }
}