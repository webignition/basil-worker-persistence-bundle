<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services\Persister;

use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackEntity;
use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;
use webignition\BasilWorker\PersistenceBundle\Services\Persister\CallbackPersister;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class CallbackPersisterTest extends AbstractFunctionalTest
{
    private CallbackPersister $callbackPersister;

    protected function setUp(): void
    {
        parent::setUp();

        $callbackPersister = $this->container->get(CallbackPersister::class);
        self::assertInstanceOf(CallbackPersister::class, $callbackPersister);
        if ($callbackPersister instanceof CallbackPersister) {
            $this->callbackPersister = $callbackPersister;
        }
    }

    public function testPersist()
    {
        $callback = CallbackEntity::create(CallbackInterface::TYPE_COMPILE_FAILURE, []);

        self::assertNull($callback->getId());
        $this->callbackPersister->persist($callback);
        self::assertIsInt($callback->getId());
    }
}
