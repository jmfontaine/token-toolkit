<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package    TokenToolkit
 * @subpackage Token
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace TokenToolkit\Token;

/**
 * Class representing a T_CONSTANT_ENCAPSED_STRING token
 *
 * @package    TokenToolkit
 * @subpackage Token
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class ConstantEncapsedStringToken extends AbstractTokenWithoutInnerScope
{
    protected $name = 'T_CONSTANT_ENCAPSED_STRING';

    public function getContent()
    {
        return substr(parent::getContent(), 1, -1);
    }

    public function getRawContent()
    {
        return parent::getContent();
    }
}
