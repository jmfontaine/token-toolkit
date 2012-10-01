<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package   TokenToolkit
 * @subpackage Tests
 * @author    Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace TokenToolkit\Tests;

use TokenToolkit\Autoloader;
use TokenToolkit\TokenStack;

class AutoloaderTest extends \PHPUnit_Framework_TestCase
{
    /*
     * Methods
     */

    /**
     * @test
     * @runTestInSeparateProcess true
     */
    public function registersAutoloader()
    {
        // Unregister existing autoloaders to avoid biased tests
        $autoloaders = spl_autoload_functions();
        foreach ($autoloaders as $autoloader) {
            spl_autoload_unregister($autoloader);
        }

        $autoloader = new Autoloader();
        $autoloader->register();

        $this->assertSame(
            array(
                array($autoloader, 'load'),
            ),
            spl_autoload_functions()
        );
    }

    /**
     * @test
     */
    public function autoloaderCanLoadAClass()
    {
        // Unregister existing autoloaders to avoid biased tests
        $autoloaders = spl_autoload_functions();
        foreach ($autoloaders as $autoloader) {
            spl_autoload_unregister($autoloader);
        }

        $autoloader = new Autoloader();
        $autoloader->register();

        $stack = new TokenStack('<?php $a = "dummy"; ?>');
        $this->assertSame('TokenToolkit\TokenStack', get_class($stack));
    }

    /*
     * Bugs
     */
}
