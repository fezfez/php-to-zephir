<?php

namespace PhpToZephir\Render;

use PhpToZephir\FileWriter;

class StringRender implements RenderInterface
{
	/* (non-PHPdoc)
	 * @see \PhpToZephir\Render\RenderInterface::render()
	 */
	public function render(array $file)
	{
		return $file['zephir'];
	}
}
