<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package TokenToolkit
 * @subpackage Search
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace TokenToolkit\Search\Pattern;

use TokenToolkit\Token\TokenInterface;

/**
 *
 *
 * @package TokenToolkit
 * @subpackage Search
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
interface PatternInterface
{
    /**
     * Returns search pattern name
     *
     * @return string Search pattern name
     */
    public function getName();

    /**
     * Checks if a token matches search pattern
     *
     * @param TokenInterface $token Token to check
     * @return bool True if token matches search pattern, false otherwise
     */
    public function match(TokenInterface $token);
}
