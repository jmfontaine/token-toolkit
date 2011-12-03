<?php
set_include_path(__DIR__ . PATH_SEPARATOR . get_include_path());

require_once __DIR__ . '/PhpTokenToolkit/Autoloader.php';
use PhpTokenToolkit\Autoloader;
$autoloader = new Autoloader();
$autoloader->register();