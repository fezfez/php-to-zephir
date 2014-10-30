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
     */
    public function convert($class)
    {
        $rc = new \ReflectionClass($class);

        $filePath = $rc->getFileName();

        return $this->converter->convert($this->parser->parse(file_get_contents($filePath)), $class);
    }
}