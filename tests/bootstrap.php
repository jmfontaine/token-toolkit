<?php
/**
 * This file is part of the TokenToolkit library.
 *
 * @package   TokenToolkit
 * @copyright 2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 */
$rootDir = dirname(__DIR__);

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('TokenToolkit', array($rootDir.'/src/', $rootDir.'/tests/'));
$loader->register();