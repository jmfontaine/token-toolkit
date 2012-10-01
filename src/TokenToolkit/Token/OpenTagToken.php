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
 * Class representing a T_OPEN_TAG token
 *
 * @package    TokenToolkit
 * @subpackage Token
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class OpenTagToken extends AbstractTokenWithoutInnerScope
{
    protected $name = 'T_OPEN_TAG';
}
