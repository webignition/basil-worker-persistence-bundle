<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use webignition\BasilWorker\PersistenceBundle\PersistenceBundle;

class PersistenceBundleTestingKernel extends Kernel
{
    /**
     * @return BundleInterface[]
     */
    public function registerBundles(): array
    {
        return [
            new PersistenceBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }
}
