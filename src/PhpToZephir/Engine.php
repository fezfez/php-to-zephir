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
     * @var Logger
     */
    private $logger = null;

    /**
     * @param Parser $parser
     * @param Converter\Converter $converter
     * @param ClassCollector $classCollector
     * @param Logger $logger
     */
    public function __construct(Parser $parser, \PhpToZephir\Converter\Converter $converter, ClassCollector $classCollector, Logger $logger)
    {
        $this->parser = $parser;
        $this->converter = $converter;
        $this->classCollector = $classCollector;
        $this->logger = $logger;
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

    /**
     * @param string $dir
     */
    private function findFiles($dir)
    {
        $directory = new \RecursiveDirectoryIterator($dir);
        $iterator = new \RecursiveIteratorIterator($directory);
        $regex = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        return $regex;
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

        $files = $this->findFiles($dir);

        $count = iterator_count($files);
        $this->logger->log('Collect class names');
        $progress = $this->logger->progress($count);
        foreach ($files as $filei) {
            $file = $filei[0];
            try {
                $classes[$file] = $this->classCollector->collect($this->parser->parse(file_get_contents($file)), $file);
            } catch (\Exception $e) {
                $this->logger->log(
                    sprintf('Could not convert file "%s" cause : %s %s %s' . "\n", $file, $e->getMessage(), $e->getFile(), $e->getLine())
                );
            }
            $progress->advance();
        }

        $progress->finish();

        $this->logger->log("\nConvert php to zep");
        $progress = $this->logger->progress(count($classes));

        foreach ($classes as $phpFile => $class) {
            if ($filterFileName !== null) {
                if (basename($phpFile, '.php') !== $filterFileName) {
                    continue;
                }
            }

            $phpCode   = file_get_contents($phpFile);
            $fileName  = basename($phpFile, '.php');
            try {
                $converted = $this->convertCode($phpCode, $phpFile, $classes);
                $converted['class'] = $class;
            } catch (\Exception $e) {
                $this->logger->log(
                    sprintf('Could not convert file "%s" cause : %s %s %s' . "\n", $file, $e->getMessage(), $e->getFile(), $e->getLine())
                );
                $progress->advance();
                continue;
            }

            $zephirCode[$phpFile] = array_merge(
                $converted,
                array(
                    'phpPath'   => substr($phpFile, 0, strrpos($phpFile, '/')),
                    'fileName'  => $fileName,
                    'fileDestination' => $converted['class'] . '.zep'
                )
             );

            $zephirCode[$phpFile]['fileDestination'] = strtolower(str_replace('\\', '/', $zephirCode[$phpFile]['fileDestination']));

            foreach ($converted['additionalClass'] as $aditionalClass) {
                $zephirCode[$phpFile . $aditionalClass['name']] = array_merge(
                    array(
                        'fileName'  => $aditionalClass['name'],
                        'zephir' => $aditionalClass['code'],
                        'fileDestination' => strtolower(str_replace('\\', '/', $converted['namespace']) . '/' . $aditionalClass['name']) . '.zep'
                    )
                );
            }
            $progress->advance();
        }

        $progress->finish();
        $this->logger->log("\n");

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
        $converted = $this->converter->nodeToZephir($this->parser->parse($phpCode), $fileName, $classes);
        $toReturn = array(
            'zephir'    => $converted['code'],
            'php'       => $phpCode,
            'namespace' => $converted['namespace'],
            'destination' => str_replace('\\', '/', $converted['namespace']) . '/',
            'additionalClass' => $converted['additionalClass']
        );

        return $toReturn;
    }
}
