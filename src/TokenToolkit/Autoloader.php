<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package   TokenToolkit
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace TokenToolkit;


/**
 * Simple autoloader for Token Toolkit.
 *
 * @package    TokenToolkit
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>

 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class Autoloader
{
    /**
     * Loads the file containing a class.
     *
     * @see https://gist.github.com/1234504
     * @param string $class Class name
     * @return void
     */
    public function load($class)
    {
        $class                 = ltrim($class, '\\');
        $filename              = '';
        $namespace             = '';
        $lastNamespacePosition = strripos($class, '\\');
        if (false !== $lastNamespacePosition) {
            $namespace = substr($class, 0, $lastNamespacePosition);
            $class     = substr($class, $lastNamespacePosition + 1);
            $filename  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $filename .= str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

        require $filename;
    }

    /**
     * Pushes the method load() with the SPL autoload stack.
     *
     * @return void
     */
    public function register()
    {
        spl_autoload_register(array($this, 'load'));
    }
}
