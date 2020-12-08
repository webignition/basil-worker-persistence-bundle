<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services;

use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;
use webignition\BasilWorker\PersistenceBundle\Services\CallbackFactory;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class CallbackFactoryTest extends AbstractFunctionalTest
{
    private CallbackFactory $callbackFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $callbackFactory = $this->container->get(CallbackFactory::class);
        self::assertInstanceOf(CallbackFactory::class, $callbackFactory);
        if ($callbackFactory instanceof CallbackFactory) {
            $this->callbackFactory = $callbackFactory;
        }
    }

    public function testCreate()
    {
        $type = CallbackInterface::TYPE_COMPILE_FAILURE;
        $payload = [
            'key1' => 'value1',
            'key2' => [
                'key2key1' => 'value2',
                'key2key2' => 'value3',
            ],
        ];

        $callback = $this->callbackFactory->create($type, $payload);

        self::assertNotNull($callback->getId());
        self::assertSame($type, $callback->getType());
        self::assertSame($payload, $callback->getPayload());
    }
}
