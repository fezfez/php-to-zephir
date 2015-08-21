<?php

use Symfony\Component\Console\Output\BufferedOutput;
use PhpToZephir\EngineFactory;
use PhpToZephir\Logger;
use PhpToZephir\Render\FileRender;
use PhpToZephir\CodeCollector\StringCodeCollector;
use PhpToZephir\CodeValidator;
use PhpToZephir\FileWriter;

abstract class ConverterBaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PhpToZephir\Engine
     */
    private static $engine;
    /**
     * @var FileRender
     */
    private static $render;
    /**
     * @var CodeValidator
     */
    private static $codeValidator;

    /**
     * @return \PhpToZephir\Engine
     */
    private function getEngine()
    {
        if (self::$engine === null) {
            self::$engine = EngineFactory::getInstance();
        }

        return self::$engine;
    }

    /**
     * @return StringRender
     */
    private function getRender()
    {
        if (self::$render === null) {
            self::$render = new FileRender(new FileWriter());
        }

        return self::$render;
    }

    /**
     * @return CodeValidator
     */
    private function getCodeValidator()
    {
        if (self::$codeValidator === null) {
            self::$codeValidator = new CodeValidator();
        }

        return self::$codeValidator;
    }

    /**
     * @param string|array $php
     * @param string|array $zephirExpected
     */
    public function assertConvertToZephir($php, $zephir)
    {
        if (is_array($php) === false) {
            $php = array($php);
        }

        $bufferOutput = new BufferedOutput();
        $logger = new Logger($bufferOutput, false, false);

        $generated = array_values($this->getEngine()->convert(new StringCodeCollector($php), $logger));

        $this->assertCount(count($zephir), $generated, $bufferOutput->fetch());

        self::delTree('code');

        foreach ($generated as $index => $file) {
            $zephirGenerated = $this->getRender()->render($file);

            if (is_array($zephir) === true) {
                $this->assertEquals($this->showWiteSpace($zephir[$index]), $this->showWiteSpace($zephirGenerated));
            } else {
                $this->assertEquals($this->showWiteSpace($zephir), $this->showWiteSpace($zephirGenerated));
            }
        }

        $this->assertTrue($this->getCodeValidator()->isValid('code'));
    }
    
    private function showWiteSpace($string)
    {
    	return str_replace(array("\n", "\t", "\r", " "), array('\n' . "\n", '\t', '\r', '.'), $string);
    }

    public static function delTree($dir)
    {
        if (is_dir($dir) === false) {
            return;
        }
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
            $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname());
        }
    }
}
