<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AnnotatedCommand extends Command
{
    private callable $callable;

    private array $arguments;

    public function __construct(callable $callable, array $arguments)
    {
        parent::__construct();

        $this->callable = $callable;
        $this->arguments = $arguments;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            // TODO resolve $arguments
            ($this->callable)($arguments);

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            return Command::FAILURE;
        }
    }
}
