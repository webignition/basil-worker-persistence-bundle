<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services\Store;

use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;
use webignition\BasilWorker\PersistenceBundle\Services\Factory\CallbackFactory;
use webignition\BasilWorker\PersistenceBundle\Services\Store\CallbackStore;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class CallbackStoreTest extends AbstractFunctionalTest
{
    private CallbackStore $store;
    private CallbackFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $store = $this->container->get(CallbackStore::class);
        self::assertInstanceOf(CallbackStore::class, $store);
        if ($store instanceof CallbackStore) {
            $this->store = $store;
        }

        $factory = $this->container->get(CallbackFactory::class);
        self::assertInstanceOf(CallbackFactory::class, $factory);
        if ($factory instanceof CallbackFactory) {
            $this->factory = $factory;
        }
    }

    /**
     * @dataProvider getFinishedCountDataProvider
     *
     * @param array<CallbackInterface::STATE_*> $callbackStates
     */
    public function testGetFinishedCount(array $callbackStates, int $expectedFinishedCount): void
    {
        $this->createCallbacksWithStates($callbackStates);

        self::assertSame($expectedFinishedCount, $this->store->getFinishedCount());
    }

    /**
     * @return array[]
     */
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
     */
    public function testGetCompileFailureTypeCount(array $callbackTypes, int $expectedCompileFailureTypeCount): void
    {
        $this->createCallbacksWithTypes($callbackTypes);

        self::assertSame($expectedCompileFailureTypeCount, $this->store->getCompileFailureTypeCount());
    }

    /**
     * @return array[]
     */
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
            'two compile-failure, one finished-compilation-failed' => [
                'callbackTypes' => [
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_COMPILE_FAILURE,
                    CallbackInterface::TYPE_COMPILE_FAILURE,
                    CallbackInterface::TYPE_FINISHED_COMPILATION_FAILED,
                ],
                'expectedCompileFailureTypeCount' => 3,
            ],
        ];
    }

    /**
     * @dataProvider getJobTimeoutTypeCountDataProvider
     *
     * @param array<CallbackInterface::TYPE_*> $callbackTypes
     */
    public function testGetJobTimeoutTypeCount(array $callbackTypes, int $expectedJobTimeoutTypeCount): void
    {
        $this->createCallbacksWithTypes($callbackTypes);

        self::assertSame($expectedJobTimeoutTypeCount, $this->store->getJobTimeoutTypeCount());
    }

    /**
     * @return array[]
     */
    public function getJobTimeoutTypeCountDataProvider(): array
    {
        return [
            'no callbacks' => [
                'callbackTypes' => [],
                'expectedJobTimeoutTypeCount' => 0,
            ],
            'no job-timeout' => [
                'callbackTypes' => [
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                ],
                'expectedJobTimeoutTypeCount' => 0,
            ],
            'one job-timeout' => [
                'callbackTypes' => [
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_JOB_TIMEOUT,
                ],
                'expectedJobTimeoutTypeCount' => 1,
            ],
            'two job-timeout' => [
                'callbackTypes' => [
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_JOB_TIMEOUT,
                    CallbackInterface::TYPE_JOB_TIMEOUT,
                ],
                'expectedJobTimeoutTypeCount' => 2,
            ],
            'two job-timeout, one finished-job-timeout' => [
                'callbackTypes' => [
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_JOB_TIMEOUT,
                    CallbackInterface::TYPE_JOB_TIMEOUT,
                    CallbackInterface::TYPE_FINISHED_JOB_TIMEOUT,
                ],
                'expectedJobTimeoutTypeCount' => 3,
            ],
            'two job-timeout, one finished-job-timeout, one job/timed-out' => [
                'callbackTypes' => [
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_EXECUTE_DOCUMENT_RECEIVED,
                    CallbackInterface::TYPE_JOB_TIMEOUT,
                    CallbackInterface::TYPE_JOB_TIMEOUT,
                    CallbackInterface::TYPE_JOB_TIME_OUT,
                ],
                'expectedJobTimeoutTypeCount' => 3,
            ],
        ];
    }

    /**
     * @param array<CallbackInterface::STATE_*> $states
     */
    private function createCallbacksWithStates(array $states): void
    {
        foreach ($states as $state) {
            $callback = $this->factory->create(CallbackInterface::TYPE_COMPILE_FAILURE, []);
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
            $this->factory->create($type, []);
        }
    }
}
