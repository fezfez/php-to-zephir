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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use PhpToZephir\EngineFactory;
use PhpToZephir\Logger;
use PhpToZephir\FileWriter;
use PhpToZephir\CodeCollector\DirectoryCodeCollector;
use PhpToZephir\Render\FileRender;

/**
 * Generator command.
 *
 * @author Stéphane Demonchaux
 */
class ConvertDirectory extends Command
{
    protected function configure()
    {
        $this
            ->setName('phpToZephir:convertDir')
            ->setDescription('Convert a php directory to Zephir')
            ->addArgument('dir', InputArgument::REQUIRED, 'Directory to convert')
            ->addOption('debug')
            ->addArgument('file', InputArgument::OPTIONAL, 'file');
    }

    /* (non-PHPdoc)
     * @see \Symfony\Component\Console\Command\Command::execute()
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $engine = EngineFactory::getInstance(new Logger($output, $input->getOption('debug')));
        $dir = $input->getArgument('dir');
        $fileWriter = new FileWriter();

        if (is_dir($dir) === false) {
            throw new \Exception(sprintf('Directory "%s" does not exist', $dir));
        }

        $render = new FileRender(new FileWriter());

        foreach ($engine->convert(new DirectoryCodeCollector(array($dir)), $input->getArgument('file')) as $file) {
            $render->render($file);
        }
    }
}
