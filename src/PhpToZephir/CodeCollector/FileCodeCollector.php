<?php


namespace PhpToZephir\CodeCollector;

class FileCodeCollector implements CodeCollectorInterface
{
	/**
	 * @var array
	 */
	private $files;

	/**
	 * @param array $code
	 */
	public function __construct(array $files)
	{
		$this->files = $files;
	}
	
	/**
	 * @return array
	 */
	public function getCode()
	{
		$files = array();
		
		foreach ($this->files as $file) {
			$files[$file] = file_get_contents($file);
		}
		
		return $files;
	}
}