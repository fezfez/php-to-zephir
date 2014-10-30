<?php

ini_set('xdebug.max_nesting_level', 2000);

$vendorDir = __DIR__ . '/vendor';

if (false === is_file($vendorDir . '/autoload.php')) {
    throw new \Exception("You must set up the project dependencies, run the following commands:
                    wget http://getcomposer.org/composer.phar
                    php composer.phar install
                    ");
} else {
    include($vendorDir . '/autoload.php');
}

use PhpToZephir\EngineFactory;


try {
    $engine = EngineFactory::getInstance();

    $classToConvert = array(
        'Event',
        'EventDispatcher',
        'EventDispatcherInterface',
        'EventSubscriberInterface',
        'GenericEvent',
        'ImmutableEventDispatcher'
    );

    foreach ($classToConvert as $name) {
        echo 'Convert' . $name . "\n";
        $rst = $engine->convert('Symfony\Component\EventDispatcher\\' . $name);
        @mkdir('Symfony/Component/EventDispatcher/', 0777, true);
        file_put_contents('Symfony/Component/EventDispatcher/' . $name . '.zep', $rst);
    }


    // $stmts is an array of statement nodes
} catch (PhpParser\Error $e) {
    echo 'Parse Error: ', $e->getMessage();
}