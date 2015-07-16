<?php


namespace PhpToZephir\CodeCollector;

class StringCodeCollector implements CodeCollectorInterface
{
	/**
	 * @var array
	 */
	private $code;

	/**
	 * @param array $code
	 */
	public function __construct(array $code)
	{
		$this->code = $code;
	}
	
	/**
	 * @return array
	 */
	public function getCode()
	{
		return $this->code;
	}
}