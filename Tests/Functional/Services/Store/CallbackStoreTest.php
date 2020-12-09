<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services\Store;

use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;
use webignition\BasilWorker\PersistenceBundle\Services\Factory\CallbackFactory;
use webignition\BasilWorker\PersistenceBundle\Services\Store\CallbackStore;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class CallbackStoreTest extends AbstractFunctionalTest
{
    private CallbackStore $callbackStore;
    private CallbackFactory $callbackFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $callbackStore = $this->container->get(CallbackStore::class);
        self::assertInstanceOf(CallbackStore::class, $callbackStore);
        if ($callbackStore instanceof CallbackStore) {
            $this->callbackStore = $callbackStore;
        }

        $callbackFactory = $this->container->get(CallbackFactory::class);
        self::assertInstanceOf(CallbackFactory::class, $callbackFactory);
        if ($callbackFactory instanceof CallbackFactory) {
            $this->callbackFactory = $callbackFactory;
        }
    }

    /**
     * @dataProvider getFinishedCountDataProvider
     *
     * @param array<CallbackInterface::STATE_*> $callbackStates
     * @param int $expectedFinishedCount
     */
    public function testGetFinishedCount(array $callbackStates, int $expectedFinishedCount)
    {
        $this->createCallbacksWithStates($callbackStates);

        self::assertSame($expectedFinishedCount, $this->callbackStore->getFinishedCount());
    }

    public function getFinishedCountDataProvider(): array
    {
        return [
            'no callbacks' => [
                'callbackStates' => [],
                'expectedFinishedCount' => 0,
            ],
            'none finished' => [
                'callbackStates' => [
                    CallbackInterface::STATE_AWAITING,
                    CallbackInterface::STATE_QUEUED,
                    CallbackInterface::STATE_SENDING,
                ],
                'expectedFinishedCount' => 0,
            ],
            'one complete' => [
                'callbackStates' => [
                    CallbackInterface::STATE_AWAITING,
                    CallbackInterface::STATE_QUEUED,
                    CallbackInterface::STATE_SENDING,
                    CallbackInterface::STATE_COMPLETE,
                ],
                'expectedFinishedCount' => 1,
            ],
            'one failed' => [
                'callbackStates' => [
                    CallbackInterface::STATE_AWAITING,
                    CallbackInterface::STATE_QUEUED,
                    CallbackInterface::STATE_SENDING,
                    CallbackInterface::STATE_FAILED,
                ],
                'expectedFinishedCount' => 1,
            ],
            'two complete, three failed' => [
                'callbackStates' => [
                    CallbackInterface::STATE_AWAITING,
                    CallbackInterface::STATE_QUEUED,
                    CallbackInterface::STATE_SENDING,
                    CallbackInterface::STATE_COMPLETE,
                    CallbackInterface::STATE_COMPLETE,
                    CallbackInterface::STATE_FAILED,
                    CallbackInterface::STATE_FAILED,
                    CallbackInterface::STATE_FAILED,
                ],
                'expectedFinishedCount' => 5,
            ],
        ];
    }

    /**
     * @dataProvider getCompileFailureTypeCountDataProvider
     *
     * @param array<CallbackInterface::TYPE_*> $callbackTypes
     * @param int $expectedCompileFailureTypeCount
     */
    public function testGetCompileFailureTypeCount(array $callbackTypes, int $expectedCompileFailureTypeCount)
    {
        $this->createCallbacksWithTypes($callbackTypes);

        self::assertSame($expectedCompileFailureTypeCount, $this->callbackStore->getCompileFailureTypeCount());
    }

    public function getCompileFailureTypeCountDataProvider(): array
    {
        return [
            'no callbacks' => [
                'callbackTypes' => [],
                'expectedCompileFailureTypeCount' => 0,
            ],
            'no compile-failure' => [
                'callbackTypes' => [
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                ],
                'expectedCompileFailureTypeCount' => 0,
            ],
            'one compile-failure' => [
                'callbackTypes' => [
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_COMPILE_FAILURE,
                ],
                'expectedCompileFailureTypeCount' => 1,
            ],
            'two compile-failure' => [
                'callbackTypes' => [
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_COMPILE_FAILURE,
                    CallbackInterface::TYPE_COMPILE_FAILURE,
                ],
                'expectedCompileFailureTypeCount' => 2,
            ],
            'five compile-failure' => [
                'callbackTypes' => [
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_COMPILE_FAILURE,
                    CallbackInterface::TYPE_COMPILE_FAILURE,
                    CallbackInterface::TYPE_COMPILE_FAILURE,
                    CallbackInterface::TYPE_COMPILE_FAILURE,
                    CallbackInterface::TYPE_COMPILE_FAILURE,
                ],
                'expectedCompileFailureTypeCount' => 5,
            ],
        ];
    }

    /**
     * @param array<CallbackInterface::STATE_*> $states
     */
    private function createCallbacksWithStates(array $states): void
    {
        foreach ($states as $state) {
            $callback = $this->callbackFactory->create(CallbackInterface::TYPE_COMPILE_FAILURE, []);
            $callback->setState($state);

            $this->entityManager->persist($callback);
            $this->entityManager->flush();
        }
    }

    /**
     * @param array<CallbackInterface::TYPE_*> $types
     */
    private function createCallbacksWithTypes(array $types): void
    {
        foreach ($types as $type) {
            $this->callbackFactory->create($type, []);
        }
    }
}
