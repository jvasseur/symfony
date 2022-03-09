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

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpKernel\BootInterface;

/**
 * Run boot services
 *
 * @internal
 */
class ServicesBooter implements BootInterface
{
    private ContainerInterface $bootServices;
    private array $bootMethods;

    /**
     * @param string[][] $bootMethods
     */
    public function __construct(ContainerInterface $bootServices, array $bootMethods)
    {
        $this->bootServices = $bootServices;
        $this->bootMethods = $bootMethods;
    }

    public function boot(): void
    {
        foreach ($this->bootMethods[$id] as [$service, $method]) {
            $this->bootServices->get($boot)->$method();
        }
    }
}
