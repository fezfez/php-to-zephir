<?php

namespace PhpToZephir;

use Zephir\Commands\CommandGenerate;
use Zephir\Config;
use Zephir\Logger as ZephirLogger;
use Zephir\CompilerException;
use Zephir\Commands\CommandFullClean;

class CodeValidator
{
    /**
     * @param string $zephirCode
     *
     * @throws \Exception
     */
    public function isValid($namespace)
    {
        if (!defined('ZEPHIRPATH'))
            define('ZEPHIRPATH', realpath(__DIR__.'/../../vendor/phalcon/zephir').'/');

        
        $generateCommand = new CommandGenerate();
        $cleanCommand = new CommandFullClean();

        try {
            $config = new Config();
            $config->set('namespace', $namespace);
            $config->set('silent', true);
            $cleanCommand->execute($config, new ZephirLogger($config));
            $generateCommand->execute($config, new ZephirLogger($config));
        } catch (CompilerException $e) {
            throw new \Exception(sprintf('Error on %s', $e->getMessage()));
        }

        return true;
    }
}
