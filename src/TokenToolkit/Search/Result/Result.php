<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package TokenToolkit
 * @subpackage Search
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace TokenToolkit\Search\Result;

use TokenToolkit\File\File;
use TokenToolkit\Search\Pattern\PatternInterface as SearchPatternInterface;
use TokenToolkit\Token\TokenInterface;

/**
 *
 * @package TokenToolkit
 * @subpackage Search
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class Result
{
    protected $searchPattern;

    protected $token;

    public function __construct(TokenInterface $token, SearchPatternInterface $searchPattern)
    {
        $this->searchPattern = $searchPattern;
        $this->token         = $token;
    }

    public function getSearchPattern()
    {
        return $this->searchPattern;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function toArray()
    {
        $token    = $this->getToken();
        $file     = $token->getTokenStack()->getFile();
        $filePath = $file instanceof File ? $file->getPath() : '-';

        return array(
            'searchPattern' => $this->getSearchPattern()->getName(),
            'file'          => realpath($filePath),
            'token'         => $token->toArray(),
        );
    }
}
