<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Reference;

class BootServicesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('kernel_boot')) {
            return;
        }

        $services = $methods = [];

        foreach ($container->findTaggedServiceIds('kernel.boot', true) as $id => $tags) {
            $services[$id] = new Reference($id);

            foreach ($tags as $attributes) {
                if (!isset($attributes['method'])) {
                    throw new RuntimeException(sprintf('Tag "kernel.boot" requires the "method" attribute to be set on service "%s".', $id));
                }

                $methods[] = [$id, $attributes['method'], $attributes['priority'] ?? 0];
            }
        }

        if (!$methods) {
            $container->removeDefinition('kernel_boot');

            return;
        }

        usort($methods, static fn ($a, $b) => $b[2] <=> $a[2]);

        $container->findDefinition('kernel_boot')
            ->setArgument(0, new ServiceLocatorArgument($services))
            ->setArgument(1, array_map(static fn ($method) => array_slice($method, 0, 2), $methods));
    }
}
