<?php

/**
 * This file is part of the Code Generator package.
 *
 * (c) St�ｿｽphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhpToZephir\Command;

use Symfony\Component\Console\Output\OutputInterface;
use PhpToZephir\EngineFactory;
use PhpToZephir\Render\FileRender;
use PhpToZephir\FileWriter;

/**
 * Generator command.
 *
 * @author Stéphane Demonchaux
 */
class ConvertDirectoryFactory
{
    /**
     * @param OutputInterface $output
     *
     * @return \PhpToZephir\Command\ConvertDirectory
     */
    public static function getInstance(OutputInterface $output)
    {
        return new ConvertDirectory(EngineFactory::getInstance(), new FileRender(new FileWriter()), $output);
    }
}
