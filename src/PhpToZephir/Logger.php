<?php

namespace PhpToZephir;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\NullOutput;
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
     * @var bool
     */
    private $trace = null;
    /**
     * @var bool
     */
    private $progresseBar = null;

    /**
     * @param OutputInterface $output
     * @param bool            $trace
     * @param bool            $progresseBar
     */
    public function __construct(OutputInterface $output, $trace = false, $progresseBar = true)
    {
        $this->output       = $output;
        $this->trace        = $trace;
        $this->progresseBar = $progresseBar;
    }

    private function cleanProgressbar()
    {
        if ($this->progress !== null && $this->progress->getStartTime() !== null && $this->progresseBar === true) {
            $this->progress->clear();
            $this->output->writeln('');
        }
    }

    public function reDrawProgressBar()
    {
        if ($this->progress !== null && $this->progress->getStartTime() !== null && $this->progresseBar === true) {
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

            $space = 35 - strlen($message);
            $spaceAfterMessage = str_repeat(' ', (($space <= 1) ? 1 : $space));
            $spaceAfterLine = str_repeat(' ', (5 - strlen($node->getLine())));

            $this->output->writeln(
                sprintf(
                    '[%s]%s line "%s"%s class "%s"',
                    $message,
                    $spaceAfterMessage,
                    $node->getLine(),
                    $spaceAfterLine,
                    $class
                )
            );
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
     * @param int $number
     */
    public function progress($number)
    {
        $progress = new ProgressBar((($this->progresseBar === true) ? $this->output : new NullOutput()), $number);
        $progress->setFormat(ProgressHelper::FORMAT_VERBOSE);
        $progress->start();

        $this->progress = $progress;

        return $progress;
    }
}
