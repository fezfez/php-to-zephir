<?php

namespace PhpToZephir;

use Symfony\Component\Console\Output\OutputInterface;
use PhpParser\Node;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\ProgressHelper;

class Logger
{
    /**
     * @var OutputInterface
     */
    private $output = null;
    /**
     * @var ProgressBar
     */
    private $progress = null;
    /**
     * @var boolean
     */
    private $trace = null;

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output, $trace = true)
    {
        $this->output = $output;
        $this->trace  = $trace;
    }

    private function cleanProgressbar()
    {
        if ($this->progress !== null && $this->progress->getStartTime() !== null) {
            $this->progress->clear();
            $this->output->writeln("");
        }
    }

    public function reDrawProgressBar()
    {
        if ($this->progress !== null && $this->progress->getStartTime() !== null) {
            $this->progress->display();
        }
    }
    /**
     * @param string $message
     * @param Node   $node
     * @param string $class
     */
    public function logNode($message, Node $node, $class = null)
    {
        $this->cleanProgressbar();
        $this->output->writeln(
            '<comment>'.$message.' on line '.$node->getLine().' in class "'.$class.'"</comment>'
        );
        $this->reDrawProgressBar();
    }

    /**
     * @param string $message
     */
    public function trace($message, Node $node, $class = null)
    {
        if ($this->trace === true) {
            $this->cleanProgressbar();
            $this->output->writeln($message.' on line '.$node->getLine().' in class "'.$class.'"');
            $this->reDrawProgressBar();
        }
    }

    /**
     * @param string $message
     */
    public function log($message)
    {
        $this->cleanProgressbar();
        $this->output->writeln($message);
        $this->reDrawProgressBar();
    }

    /**
     * @param integer $number
     */
    public function progress($number)
    {
        $progress = new ProgressBar($this->output, $number);
        $progress->setFormat(ProgressHelper::FORMAT_VERBOSE);
        $progress->start();

        $this->progress = $progress;

        return $progress;
    }
}
