<?php

/**
 * This file is part of the PHP to Zephir package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
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
use PhpToZephir\CodeCollector\FileCodeCollector;
use PhpToZephir\Render\FileRender;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;

/**
 * Convert command.
 *
 * @author Stéphane Demonchaux
 */
class Convert extends Command
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
            ->setName('phpToZephir:convert')
            ->setDescription('Convert a php file or directory to Zephir')
            ->addArgument('source', InputArgument::REQUIRED, 'Directory/File to convert')
            ->addOption('debug', null, null, "Debug output")
            ->addOption('v', null, null, "Verbose mode")
            ->addArgument('file', InputArgument::OPTIONAL, 'Ignore a file');
    }

    /* (non-PHPdoc)
     * @see \Symfony\Component\Console\Command\Command::execute()
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $source = $input->getArgument('source');

        if (is_dir($source) === false && is_file($source) === false) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a file or a directory', $source));
        }

        $logger = new Logger($this->output, $input->getOption('debug'));

        if (is_dir($source) === true) {
            $collector = new DirectoryCodeCollector(array($source));
        } elseif (is_file($source) === true) {
            $collector = new FileCodeCollector(array($source));
        }

        foreach ($this->engine->convert($collector, $logger, $input->getArgument('file')) as $file) {
            $this->fileRender->render($file);
        }

        $table = new Table($this->output);
        
        $table->setHeaders(array(
            array(new TableCell('Incompatibility', array('colspan' => 4))),
            array('Type', 'Message', 'Line', 'Class')
        ));
        $table->setRows($logger->getIncompatibility());
        $table->render();

        if ($input->getOption('v') === true) {
            $table = new Table($this->output);

            $table->setHeaders(array(
                array(new TableCell('Log', array('colspan' => 3))),
                array('Message', 'Line', 'Class')
            ));
            $table->setRows($logger->getLogs());
            $table->render();
        }
    }
}
