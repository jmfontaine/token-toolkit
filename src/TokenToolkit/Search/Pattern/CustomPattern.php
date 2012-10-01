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

/**
 *
 *
 * @package TokenToolkit
 * @subpackage Search
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class CustomPattern extends AbstractPattern
{
    /**
     * The only purpose of this method is to make parent method public
     *
     * @see TokenToolkit\Search\Pattern.AbstractPattern::addAcceptedTokenType()
     */
    public function addAcceptedTokenType($tokenType)
    {
        return parent::addAcceptedTokenType($tokenType);
    }

    /**
     * The only purpose of this method is to make parent method public
     *
     * @see TokenToolkit\Search\Pattern.AbstractPattern::addExcludedTokenType()
     */
    public function addExcludedTokenType($tokenType)
    {
        return parent::addExcludedTokenType($tokenType);
    }
}
