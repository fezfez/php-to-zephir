<?php

/**
 * This file is part of the PHP to Zephir package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PhpToZephir\Service;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;
use PhpToZephir\Command\ConvertFactory;

/**
 * Create CLI instance.
 *
 * @author Stéphane Demonchaux
 */
class CliFactory
{
    /**
     * Create CLI instance.
     *
     * @return Application
     */
    public static function getInstance(OutputInterface $output)
    {
        $questionHelper = new QuestionHelper();
        $application = new Application('PHP to Zephir Command Line Interface', 'Beta 0.2.0');
        $application->getHelperSet()->set(new FormatterHelper(), 'formatter');
        $application->getHelperSet()->set($questionHelper, 'question');

        $application->add(ConvertFactory::getInstance($output));

        return $application;
    }
}
