<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @author    Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 */

set_include_path(__DIR__ . '/src' . PATH_SEPARATOR . get_include_path());

require_once __DIR__ . '/src/TokenToolkit/Autoloader.php';
use TokenToolkit\Autoloader;
$autoloader = new Autoloader();
$autoloader->register();
