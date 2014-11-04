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
	
	public function convert($phpCode)
	{
		return $this->convertCode($phpCode, '');
	}

    /**
     * @param string $dir
     * @return array
     */
    public function convertDirectory($dir, $baseNamespace)
    {
        $zephirCode = array();
        $fileExtension = '.php';

        foreach (glob($dir . '*' . $fileExtension) as $phpFile) {
            $class = str_replace($fileExtension, '', strstr(str_replace('/', '\\', $phpFile), $baseNamespace));
            $rcClass = new \ReflectionClass($class);

            $zephirCode[$phpFile] = array(
                'zephir'    => $this->convertCode(file_get_contents($phpFile), $class),
                'php'       => file_get_contents($phpFile),
                'phpPath'   => substr($phpFile, 0, strrpos($phpFile, '/')),
                'namespace' => $rcClass->getNamespaceName(),
                'className' => $rcClass->getShortName(),
                'class'     => $class
             );
        }

        return $zephirCode;
    }

    function rstrstr($haystack,$needle)
    {
        return substr($haystack, 0,strpos($haystack, $needle));
    }

    /**
     * @param string $phpCode
     * @return string
     */
    private function convertCode($phpCode, $class)
    {
        //try {
            $code = $this->converter->convert(
                $this->parser->parse($phpCode),
                $class
            );
        /*} catch (\Exception $e) {
            throw new \Exception(sprintf('Could not convert class "%s" cause : %s ', $class, $e->getMessage()));
        }*/

        $code = str_replace('\\\\', '\\', $code);

        return $code;
    }
}
