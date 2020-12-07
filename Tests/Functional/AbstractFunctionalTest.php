<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

abstract class AbstractFunctionalTest extends TestCase
{
    protected ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = new PersistenceBundleTestingKernel('test', true);
        $kernel->boot();

        $this->container = $kernel->getContainer();
    }
}
