<?php

namespace PhpToZephir;

class FileWriter
{
    /**
     * @param array $file
     */
    public function write(array $file)
    {
        if (is_dir(strtolower($file['destination'])) === false) {
            mkdir(strtolower($file['destination']), 0777, true);
        }

        file_put_contents(
            $file['fileDestination'],
            $file['zephir']
        );
    }
}
