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

	public function __construct(OutputInterface $output)
	{
        $this->output = $output;
	}

	public function logNode($message, Node $node, $class = null)
	{
        $this->output->writeln($message . ' on line ' . $node->getLine() . ' in class "' . $class . '"');
	}

	public function trace($message, Node $node, $class = null)
	{
        // $this->output->writeln($message . ' on line ' . $node->getLine() . ' in class "' . $class . '"');
	}
}