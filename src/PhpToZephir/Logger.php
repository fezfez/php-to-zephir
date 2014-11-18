<?php

namespace PhpToZephir;

use Symfony\Component\Console\Output\OutputInterface;
use PhpParser\Node;


class Logger
{
    /**
     * @var OutputInterface
     */
    private $output = null;

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param string $message
     * @param Node $node
     * @param string $class
     */
    public function logNode($message, Node $node, $class = null)
    {
        $this->output->writeln(
            '<comment>' . $message . ' on line ' . $node->getLine() . ' in class "' . $class . '"</comment>'
        );
    }

    public function trace($message, Node $node, $class = null)
    {
        // $this->output->writeln($message . ' on line ' . $node->getLine() . ' in class "' . $class . '"');
    }
}
