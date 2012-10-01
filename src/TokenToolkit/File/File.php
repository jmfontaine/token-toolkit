<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package TokenToolkit
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace TokenToolkit\File;

use TokenToolkit\Search\Query as SearchQuery;
use TokenToolkit\TokenStack;

/**
 * PHP file representation
 *
 * @package TokenToolkit
 * @subpackage File
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class File
{
    protected $eolCharacter;

    protected $path;

    protected $tokenStack;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getEolCharacter()
    {
        if (null === $this->eolCharacter) {
            $handle = fopen($this->path, 'r');
            if (false === $handle) {
                throw new \InvalidArgumentException(
                    "File $this->path is not readable"
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

    public function getExtension()
    {
        $lastColonPosition = strrpos($this->path, '.');
        if (false === $lastColonPosition) {
            return '';
        }

        return substr($this->path, $lastColonPosition);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getSource()
    {
        $source = file_get_contents($this->path);

        return $source;
    }

    public function getTokenStack()
    {
        if (null === $this->tokenStack) {
            $this->tokenStack = new TokenStack(
                $this,
                $this->getEolCharacter()
            );
        }

        return $this->tokenStack;
    }

    public function search($searchPatterns, $direction = SearchQuery::FORWARD)
    {
        // Make sure we have an array in the end
        if (is_object($searchPatterns)) {
            $searchPatterns = array($searchPatterns);
        }

        $searchQuery = new SearchQuery($this->getTokenStack(), $searchPatterns, $this);
        $searchQuery->setDirection($direction);

        return $searchQuery->search();
    }

    public function toArray()
    {
        return array(
            'file'   => $this->getPath(),
            'tokens' => $this->getTokenStack()->toArray(),
        );
    }
}
