<?php

namespace PhpToZephir\Render;

use PhpToZephir\FileWriter;

class FileRender implements RenderInterface
{
    /**
     * @var FileWriter
     */
    private $fileWriter;

    /**
     * @param FileWriter $fileWriter
     */
    public function __construct(FileWriter $fileWriter)
    {
        $this->fileWriter = $fileWriter;
    }

    /* (non-PHPdoc)
     * @see \PhpToZephir\Render\RenderInterface::render()
     */
    public function render(array $file)
    {
        $this->fileWriter->write($file);
        
        return $file['zephir'];
    }
}
