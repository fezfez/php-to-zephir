<?php

namespace PhpToZephir;

use Zephir\Commands\CommandGenerate;
use Zephir\Config;
use Zephir\Logger as ZephirLogger;
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
        $currentDir = getcwd();

        chdir(FileWriter::BASE_DESTINATION . $namespace);

        if (!defined('ZEPHIRPATH'))
            define('ZEPHIRPATH', realpath(__DIR__.'/../../vendor/phalcon/zephir').'/');

        $generateCommand = new CommandGenerate();
        $cleanCommand = new CommandFullClean();

        try {
            $config = new Config();
            $config->set('namespace', strtolower($namespace));
            $config->set('silent', true);

            if (is_dir('ext')) {
                $cleanCommand->execute($config, new ZephirLogger($config));
            }
            $generateCommand->execute($config, new ZephirLogger($config));
        } catch (Exception $e) {
            chdir($currentDir);
            throw new \Exception(sprintf('Error on %s', $e->getMessage()));
        }

        chdir($currentDir);

        return true;
    }
}
