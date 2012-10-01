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
 * Class representing a T_STATIC token
 *
 * @package    TokenToolkit
 * @subpackage Token
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class StaticToken extends AbstractTokenWithoutInnerScope
{
    protected $name = 'T_STATIC';
}
