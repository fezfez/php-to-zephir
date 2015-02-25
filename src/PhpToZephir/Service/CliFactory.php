<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) St�phane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpToZephir\Service;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use PhpToZephir\Command\ConvertDirectory;

/**
 * Create CLI instance.
 *
 * @author St�phane Demonchaux
 */
class CliFactory
{
    /**
     * Create CLI instance.
     *
     * @return \Symfony\Component\Console\Application
     */
    public static function getInstance()
    {
        $questionHelper = new QuestionHelper();
        $application    = new Application('Code Generator Command Line Interface', 'Alpha');
        $application->getHelperSet()->set(new FormatterHelper(), 'formatter');
        $application->getHelperSet()->set($questionHelper, 'question');

        $application->add(new ConvertDirectory());

        return $application;
    }
}
