<?php

namespace PhpToZephir;

use PhpParser\Parser;
use PhpToZephir\Converter;

class Engine
{
    /**
     * @var Parser
     */
    private $parser = null;
    /**
     * @var Converter
     */
    private $converter = null;

    /**
     * @param Parser $parser
     * @param Converter $converter
     */
    public function __construct(Parser $parser, Converter $converter)
    {
        $this->parser = $parser;
        $this->converter = $converter;
    }

    /**
     * @param string $class
     * @return string
     */
    public function convertClass($class)
    {
        $rc = new \ReflectionClass($class);

        $phpCode = file_get_contents($rc->getFileName());

        return $this->convertCode($phpCode, $class);
    }

    /**
     * @param string $dir
     * @return array
     */
    public function convertDirectory($dir)
    {
        $zephirCode = array();

        foreach (glob($dir . '*.php') as $phpFile) {
            $classes = get_declared_classes();
            include $phpFile;
            $diff = array_diff(get_declared_classes(), $classes);
            $class = reset($diff);

            $zephirCode[$phpFile] = array(
                'zephir'   => $this->convertCode(file_get_contents($phpFile), $class),
                'php'      => substr($phpFile, 0, strrpos($phpFile, '/')),
                'path'     => substr($phpFile, 0, strrpos($phpFile, '/')),
                'fileName' => strstr($phpFile, '/'),
             );
        }

        return $zephirCode;
    }

    /**
     * @param string $phpCode
     * @return string
     */
    private function convertCode($phpCode, $class)
    {
        try {
            $code = $this->converter->convert(
                $this->parser->parse($phpCode),
                $class
            );
        } catch (\Exception $e) {
            throw new \Exception(sprintf('Could not convert class "%s" cause : %s ', $class, $e->getMessage()));
        }

        $code = str_replace('\\\\', '\\', $code);
        // replace $fezfez = 'fff'; by let $fezfez = 'fff';
        $code = preg_replace('/(?<=)\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff].*\=.*\;/iu', 'let $0', $code);

        // replace the class variable with non let
        $code = preg_replace('/(private|public|protected|const) let ((?<=)\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff].*\=.*\;)/iu', '$1 $2', $code);

        return $code;
    }
}
