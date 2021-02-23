<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services\Factory;

use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;
use webignition\BasilWorker\PersistenceBundle\Services\Factory\CallbackFactory;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class CallbackFactoryTest extends AbstractFunctionalTest
{
    private CallbackFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $factory = $this->container->get(CallbackFactory::class);
        self::assertInstanceOf(CallbackFactory::class, $factory);
        if ($factory instanceof CallbackFactory) {
            $this->factory = $factory;
        }
    }

    public function testCreate(): void
    {
        $type = CallbackInterface::TYPE_COMPILE_FAILURE;
        $payload = [
            'key1' => 'value1',
            'key2' => [
                'key2key1' => 'value2',
                'key2key2' => 'value3',
            ],
        ];

        $callback = $this->factory->create($type, $payload);

        self::assertNotNull($callback->getId());
        self::assertSame($type, $callback->getType());
        self::assertSame($payload, $callback->getPayload());
    }
}
