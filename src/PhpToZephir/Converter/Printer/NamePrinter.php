<?php

namespace PhpToZephir\Converter\Printer;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node\Name;
use PhpToZephir\Converter\Manipulator\ClassManipulator;
use PhpToZephir\ReservedWordReplacer;

class NamePrinter
{
	/**
	 * @var ReservedWordReplacer
	 */
	private $reservedWordReplacer = null;
	
	/**
	 * @param ReservedWordReplacer $reservedWordReplacer
	 */
	public function __construct(ReservedWordReplacer $reservedWordReplacer)
	{
		$this->reservedWordReplacer = $reservedWordReplacer;
	}
	
    /**
     * @return string
     */
    public static function getType()
    {
        return "pName";
    }

    /**
     * @param Name $node
     *
     * @return Ambigous <string, unknown>
     */
    public function convert(Name $node)
    {
    	return $this->reservedWordReplacer->replace(implode('\\', $node->parts));
    }
}
