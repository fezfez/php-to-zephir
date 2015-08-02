<?php


namespace PhpToZephir\CodeCollector;

class DirectoryCodeCollector implements CodeCollectorInterface
{
	/**
	 * @var array
	 */
	private $directories;

	/**
	 * @param array $code
	 */
	public function __construct(array $directories)
	{
		$this->directories = $directories;
	}
	
	/**
	 * @param string $dir
	 * @return \RegexIterator
	 */
	private function findFiles($dir)
	{
		$directory = new \RecursiveDirectoryIterator($dir);
		$iterator = new \RecursiveIteratorIterator($directory);
		$regex = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);
	
		return $regex;
	}
	
	/**
	 * @return array
	 */
	public function getCode()
	{
		$files = array();
		
		foreach ($this->directories as $directory) {
			foreach ($this->findFiles($directory) as $file) {
				$files[$file] = file_get_contents($file);
			}
		}
		
		return $files;
	}
}