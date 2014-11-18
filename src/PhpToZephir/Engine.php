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
     * @var ClassCollector
     */
    private $classCollector = null;

    /**
     * @param Parser $parser
     * @param Converter $converter
     * @param ClassCollector $classCollector
     */
    public function __construct(Parser $parser, Converter $converter, ClassCollector $classCollector)
    {
        $this->parser = $parser;
        $this->converter = $converter;
        $this->classCollector = $classCollector;
    }

    /**
     * @param string $class
     * @return string
     */
    public function convertClass($class)
    {
        $rc = new \ReflectionClass($class);

        $phpCode = file_get_contents($rc->getFileName());

        return $this->convertCode($phpCode);
    }

    public function convert($phpCode)
    {
        return $this->convertCode($phpCode);
    }

    private function findFiles($dir, array $files = array(), $recursive = true)
    {
        $fileExtension = '.php';

        foreach (glob($dir . '*' . $fileExtension) as $phpFile) {
            $files[] = $phpFile;
        }

        if ($recursive === true) {
            $paths = glob($dir. '*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
            foreach ($paths as $recursiveDir) {
                $files = $this->findFiles($recursiveDir, $files, $recursive);
            }
        }

        return $files;
    }
    /**
     * @param string $dir
     * @return array
     */
    public function convertDirectory($dir, $recursive = true, $filterFileName = null)
    {
        $zephirCode = array();
        $fileExtension = '.php';
        $classes = array();

        $files = $this->findFiles($dir, array(), $recursive);

        foreach ($files as $file) {
            $classes[] = $this->classCollector->collect($this->parser->parse(file_get_contents($file)));
        }

        foreach ($files as $phpFile) {

            if ($filterFileName !== null) {
                if (basename($phpFile, '.php') !== $filterFileName) {
                    continue;
                }
            }

            $phpCode   = file_get_contents($phpFile);
            $fileName  = basename($phpFile, '.php');
            $converted = $this->convertCode($phpCode, $phpFile, $classes);

            $zephirCode[$phpFile] = array_merge(
                $converted,
                array(
                    'phpPath'   => substr($phpFile, 0, strrpos($phpFile, '/')),
                    'fileName'  => $fileName,
                    'fileDestination' => strtolower(str_replace('\\', '/', $converted['namespace']) . '/' . $converted['class']) . '.zep'
                )
             );

            foreach ($converted['additionalClass'] as $aditionalClass) {
                $zephirCode[$phpFile . $aditionalClass['name']] = array_merge(
                    array(
                        'fileName'  => $aditionalClass['name'],
                        'zephir' => $aditionalClass['code'],
                        'fileDestination' => strtolower(str_replace('\\', '/', $converted['namespace']) . '/' . $aditionalClass['name']) . '.zep'
                    )
                );
            }
        }

        return $zephirCode;
    }

    private function rstrstr($haystack,$needle)
    {
        return substr($haystack, 0, strpos($haystack, $needle));
    }

    /**
     * @param string $phpCode
     * @return string
     */
    private function convertCode($phpCode, $fileName = null, array $classes = array())
    {
        //try {
            $converter = clone $this->converter;
            $converted = $converter->prettyPrint($this->parser->parse($phpCode), $fileName, $classes);
            $toReturn = array(
                'zephir'    => $converted['code'],
                'php'       => $phpCode,
                'namespace' => $converted['namespace'],
                'class'     => $converted['class'],
                'destination' => str_replace('\\', '/', $converted['namespace']) . '/',
                'additionalClass' => $converted['additionalClass']
            );
            // replace reserved work

        /*} catch (\Exception $e) {
            throw new \Exception(sprintf('Could not convert class "%s" cause : %s ', $class, $e->getMessage()));
        }*/

        return $toReturn;
    }
}
