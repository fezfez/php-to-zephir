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
    public function convert($class)
    {
        $rc = new \ReflectionClass($class);

        $code = $this->converter->convert(
            $this->parser->parse(
                file_get_contents($rc->getFileName())
            ),
            $class
        );

        $code = str_replace('\\\\', '\\', $code);
        // replace $fezfez = 'fff'; by let $fezfez = 'fff';
        $code = preg_replace('/(?<=)\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff].*\=.*\;/iu', 'let $0', $code);

        // replace the class variable with non let
        $code = preg_replace('/(private|public|protected|const) let ((?<=)\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff].*\=.*\;)/iu', '$1 $2', $code);

        return $code;
    }
}
