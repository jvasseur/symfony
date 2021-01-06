<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\DependencyInjection;

use Symfony\Component\Console\Attribute\Command;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

class AddAnnotatedConsoleCommandPass implements CompilerPassInterface
{
    private string $annotatedCommandTag;

    public function __construct(string $annotatedCommandTag = 'console.annotated_command')
    {
        $this->annotatedCommandTag = $annotatedCommandTag;
    }

    public function process(ContainerBuilder $container)
    {
        $commandServices = $container->findTaggedServiceIds($this->annotatedCommandTag, true);

        foreach ($commandServices as $id => $tags) {
            $definition = $container->getDefinition($id);
            $class = $container->getParameterBag()->resolveValue($definition->getClass());

            if (!$reflection = $container->getReflectionClass($class)) {
                throw new InvalidArgumentException(sprintf('Class "%s" used for service "%s" cannot be found.', $class, $id));
            }

            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $commandAttributes = $reflection->getAttributes(Command::class);

                if (!isset($commandAttributes[0])) {
                    continue;
                }

                $commandAttribute = $commandAttributes[0];
            }
        }
    }
}
