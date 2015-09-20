<?php

/**
 * This file is part of the PHP to Zephir package.
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
 * Convert command.
 *
 * @author Stéphane Demonchaux
 */
class ConvertFactory
{
    /**
     * @param OutputInterface $output
     *
     * @return \PhpToZephir\Command\Convert
     */
    public static function getInstance(OutputInterface $output)
    {
        return new Convert(EngineFactory::getInstance(), new FileRender(new FileWriter()), $output);
    }
}
