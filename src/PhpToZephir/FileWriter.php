<?php

namespace PhpToZephir;

class FileWriter
{
    const BASE_DESTINATION = 'converted/';

    /**
     * Config template
     *
     * @var string
     */
    private $configTemplate = '{
    "warnings": {
        "unused-variable": true,
        "unused-variable-external": false,
        "possible-wrong-parameter": true,
        "possible-wrong-parameter-undefined": true,
        "nonexistent-function": true,
        "nonexistent-class": true,
        "non-valid-isset": true,
        "non-array-update": true,
        "non-valid-objectupdate": true,
        "non-valid-fetch": true,
        "invalid-array-index": true,
        "non-array-append": true,
        "invalid-return-type": true,
        "unrecheable-code": true,
        "nonexistant-constant": true,
        "not-supported-magic-constant": true,
        "non-valid-decrement": true,
        "non-valid-increment": true,
        "non-valid-clone": true,
        "non-array-access": true,
        "invalid-reference": true
    },
    "optimizations": {
        "static-type-inference": true,
        "static-type-inference-second-pass": true,
        "local-context-pass": true,
        "constant-folding": true,
        "static-constant-class-folding": true,
        "private-internal-methods": true
    },
    "namespace": "%s",
    "name": "%s",
    "description": "my description",
    "author": "my name",
    "version": "0.0.1",
    "verbose": true
}
';
    
    /**
     * @param array $file
     */
    public function write(array $file)
    {
        $namespace = strstr($file['destination'], '/', true) . '/';

        if (is_dir(self::BASE_DESTINATION . $namespace . $file['destination']) === false) {
            mkdir(self::BASE_DESTINATION . $namespace . $file['destination'], 0777, true);
        }

        $this->createConfig($namespace);

        file_put_contents(
            self::BASE_DESTINATION . $namespace . $file['fileDestination'],
            $file['zephir']
        );
    }
    
    /**
     * Create config file for zephir
     *
     * @param string $namespace
     */
    public function createConfig($namespace)
    {
        file_put_contents(
            FileWriter::BASE_DESTINATION . $namespace . $namespace . '/config.json',
            sprintf($this->configTemplate, $namespace, $namespace)
        );
    }
}
