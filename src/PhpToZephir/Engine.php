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

        return $this->convertCode($phpCode);
    }

	public function convert($phpCode)
	{
		return $this->convertCode($phpCode);
	}

    /**
     * @param string $dir
     * @return array
     */
    public function convertDirectory($dir)
    {
        $zephirCode = array();
        $fileExtension = '.php';

        foreach (glob($dir . '*' . $fileExtension) as $phpFile) {

        	$phpCode  = file_get_contents($phpFile);
        	$fileName = $this->replaceReservedWords(basename($phpFile, '.php'));
        	$converted = $this->convertCode($phpCode, $phpFile);

            $zephirCode[$phpFile] = array(
                'zephir'    => $converted['code'],
                'php'       => $phpCode,
                'phpPath'   => substr($phpFile, 0, strrpos($phpFile, '/')),
                'fileName'  => $fileName,
            	'namespace' => $converted['namespace'],
            	'destination' => str_replace('\\', '/', $converted['namespace']) . '/'
             );
        }

        return $zephirCode;
    }

    function rstrstr($haystack,$needle)
    {
        return substr($haystack, 0,strpos($haystack, $needle));
    }

    private function replaceReservedWords($code)
    {
    	$code = str_replace('inline', 'inlinee', $code);
    	$code = str_replace('Inline', 'Inlinee', $code);

    	return $code;
    }
    /**
     * @param string $phpCode
     * @return string
     */
    private function convertCode($phpCode, $fileName = null)
    {
        //try {
        	$converted = $this->converter->prettyPrint($this->parser->parse($phpCode), $fileName);
            $converted['code'] = $this->replaceReservedWords($converted['code']);
            // replace reserved work

        /*} catch (\Exception $e) {
            throw new \Exception(sprintf('Could not convert class "%s" cause : %s ', $class, $e->getMessage()));
        }*/

        return $converted;
    }
}
