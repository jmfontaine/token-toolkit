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
 * Signature dumper
 *
 * This dumper creates a visual signature PHP code using some of its key elements.
 * This is based on an article by Ward Cunningham (http://c2.com/doc/SignatureSurvey/).
 *
 * @package TokenToolkit
 * @subpackage Dumper
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class Signature extends AbstractDumper
{
    const BASIC    = 'basic';
    const EXTENDED = 'extended';

    const BASH     = 'bash';
    const DISABLED = 'disabled';
    const HTML     = 'html';

    private $style = self::BASIC;

    private $colorMode = self::DISABLED;

    private function colorize($text, $level, $colorMode)
    {
        switch ($colorMode) {
            case self::BASH:
                if (-1 === $level) {
                    $prefix = "\033[37m";
                } elseif (3 > $level) {
                    $prefix = "\033[32m";
                } elseif (4 > $level) {
                    $prefix = "\033[33m";
                } else {
                    $prefix = "\033[31m";
                }
                $suffix = "\033[0m";
                break;
            case self:HTML:
                if (-1 === $level) {
                    $prefix = '<span style="color:#FFF">';
                } elseif (3 > $level) {
                    $prefix = '<span style="color:#0F0">';
                } elseif (4 > $level) {
                    $prefix = '<span style="color:#FFA500">';
                } else {
                    $prefix = '<span style="color:#F00">';
                }
                $suffix = '</span>';
                break;
            default:
                throw new \RuntimeException("Invalid color mode ($colorMode)");
        }

        return $prefix . $text . $suffix;
    }

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
        $basicTokenTypes = array(
            T_OPEN_CURLY_BRACKET  => '{',
            T_CLOSE_CURLY_BRACKET => '}',
            T_SEMICOLON           => ';',
        );

        $extendedTokenTypes = array(
            T_CLASS    => 'C',
            T_FUNCTION => 'F',
        );

        $colorMode = $this->getColorMode();
        $style     = $this->getStyle();

        if (self::EXTENDED === $style) {
            $allowedTokenTypes = $basicTokenTypes + $extendedTokenTypes;
        } else {
            $allowedTokenTypes = $basicTokenTypes;
        }

        $result = '';
        foreach ($tokenStack as $token) {
            $type           = $token->getType();
            $isExtendedType = array_key_exists($type, $extendedTokenTypes);
            if (array_key_exists($type, $allowedTokenTypes)) {
                if (self::DISABLED === $colorMode) {
                    $result .= $allowedTokenTypes[$type];
                } else {
                    $result .= $this->colorize(
                        $allowedTokenTypes[$type],
                        $isExtendedType ? -1 : $token->getLevel(),
                        $colorMode
                    );
                }
            }
        }

        return $result;
    }

    public function getStyle()
    {
        return $this->style;
    }

    public function getColorMode()
    {
        return $this->colorMode;
    }

    public function setColorMode($colorMode)
    {
        $allowedColorModes = array(self::BASH, self::HTML, self::DISABLED);

        if (!in_array($colorMode, $allowedColorModes)) {
            throw new \InvalidArgumentException("Invalid color mode ($colorMode)");
        }

        $this->colorMode = $colorMode;

        return $this;
    }

    public function setStyle($style)
    {
        if (self::BASIC !== $style && self::EXTENDED !== $style) {
            throw new \InvalidArgumentException("Invalid style ($style)");
        }

        $this->style = $style;

        return $this;
    }
}
