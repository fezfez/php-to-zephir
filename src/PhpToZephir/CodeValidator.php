<?php

namespace PhpToZephir;

class CodeValidator
{
	public function isValid($zephirCode)
	{
		$tmpfname = __DIR__ . '/tmp.zep';
		
		file_put_contents($tmpfname, $zephirCode);

		$ZEPHIRPATH = realpath(__DIR__ . '/../../vendor/phalcon/zephir') . '/';
		
		if (PHP_OS == "WINNT") {
			$zephirParserBinary = $ZEPHIRPATH . 'bin\zephir-parser.exe';
		} else {
			$zephirParserBinary = $ZEPHIRPATH . 'bin/zephir-parser';
		}
		
		exec($zephirParserBinary . ' ' . $tmpfname, $output, $return);
		
		unlink($tmpfname);
		
		if ($return !== 0) {
			throw new \Exception(sprintf('Error on %s', $zephirCode));
		}
	}
}