<?php

use Symfony\Component\Console\Output\BufferedOutput;
use PhpToZephir\EngineFactory;
use PhpToZephir\Logger;
use PhpToZephir\Render\StringRender;
use PhpToZephir\CodeCollector\StringCodeCollector;
use PhpToZephir\CodeValidator;

abstract class ConverterBaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PhpToZephir\Engine
     */
    private static $engine;
    /**
     * @var StringRender
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
            self::$render = new StringRender();
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
        $logger = new Logger($bufferOutput, false);

        $generated = array_values($this->getEngine()->convert(new StringCodeCollector($php), $logger));
        
        $this->assertCount(count($zephir), $generated, $bufferOutput->fetch());
        
        foreach ($generated as $index => $file) {
            $zephirGenerated = $this->getRender()->render($file);
            $this->assertTrue($this->getCodeValidator()->isValid($zephirGenerated));

            if (is_array($zephir) === true) {
                $this->assertEquals($zephir[$index], $zephirGenerated);
            } else {
                $this->assertEquals($zephir, $zephirGenerated);
            }
        }
    }
}
