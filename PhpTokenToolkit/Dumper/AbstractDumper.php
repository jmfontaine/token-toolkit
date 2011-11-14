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
 * @package PhpTokenToolkit
 * @subpackage Dumper
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace PhpTokenToolkit\Dumper;

use PhpTokenToolkit\Token\TokenInterface;

/**
 * Interface for data dumpers
 *
 * @package PhpTokenToolkit
 * @subpackage Dumper
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
abstract class AbstractDumper implements DumperInterface
{
    public function dump($object)
    {
        switch (get_class($object)) {
            case 'PhpTokenToolkit\File\File':
                $data = $this->dumpFile($object);
                break;
            case 'PhpTokenToolkit\File\FileSet':
                $data = $this->dumpFileSet($object);
                break;
            case 'PhpTokenToolkit\Search\Result\Result':
                $data = $this->dumpSearchResult($object);
                break;
            case 'PhpTokenToolkit\Search\Result\ResultSet':
                $data = $this->dumpSearchResultSet($object);
                break;
            case 'PhpTokenToolkit\TokenStack':
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