<?php

namespace PhpToZephir\Render;

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
