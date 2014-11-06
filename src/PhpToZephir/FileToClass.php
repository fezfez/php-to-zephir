<?php

if (count($argv) !== 3 || isset($argv[1]) === false || $argv[1]  !== '--file')  {
	echo 'wrong use';exit;
}

$file = $argv[2];

if (is_file($file) === false) {
	echo "$file is not a file\n";
	exit;
}

include(getcwd() . '/vendor/autoload.php');
$classes = get_declared_classes();
include($file);
$diff = array_diff(get_declared_classes(), $classes);
var_dump($diff);
echo reset($diff);
