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
use PhpToZephir\Engine;
use PhpToZephir\Logger;
use PhpToZephir\CodeCollector\DirectoryCodeCollector;
use PhpToZephir\Render\FileRender;

/**
 * Generator command.
 *
 * @author Stéphane Demonchaux
 */
class ConvertDirectory extends Command
{
    /**
     * @var Engine
     */
    private $engine;
    /**
     * @var FileRender
     */
    private $fileRender;
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @param Engine          $engine
     * @param FileRender      $fileRender
     * @param OutputInterface $output
     */
    public function __construct(Engine $engine, FileRender $fileRender, OutputInterface $output)
    {
        $this->engine = $engine;
        $this->fileRender = $fileRender;
        $this->output = $output;
        parent::__construct();
    }

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
        $directory = $input->getArgument('dir');

        if (is_dir($directory) === false) {
            throw new \InvalidArgumentException(sprintf('Directory "%s" does not exist', $directory));
        }

        $logger = new Logger($this->output, $input->getOption('debug'));
        $directoryCollector = new DirectoryCodeCollector(array($directory));

        foreach ($this->engine->convert($directoryCollector, $logger, $input->getArgument('file')) as $file) {
            $this->fileRender->render($file);
        }
    }
}
