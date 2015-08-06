<?php

namespace PhpToZephir\Tests;

error_reporting(-1);

ini_set('xdebug.max_nesting_level', 200);

/*
 * This file bootstraps the test environment.
*/


ini_set('memory_limit', '512M');
$vendorDir = __DIR__ . '/../vendor';
if (false === is_file($vendorDir . '/autoload.php')) {
	throw new \Exception("You must set up the project dependencies, run the following commands:
                    wget http://getcomposer.org/composer.phar
                    php composer.phar install
                    ");
} else {
	include $vendorDir . '/autoload.php';
}
// register silently failing autoloader
spl_autoload_register(function ($class) {
	if (0 === strpos($class, 'PhpToZephir\Tests\\')) {
		$path = __DIR__ . '/' . strtr($class, '\\', '/') . '.php';
		if (is_file($path) === true && is_readable($path) === true) {
			require_once $path;
			return true;
		}
	}
});

require __DIR__.'/../vendor/autoload.php';
require __DIR__ . '/Converter/ConverterBaseTest.php';
