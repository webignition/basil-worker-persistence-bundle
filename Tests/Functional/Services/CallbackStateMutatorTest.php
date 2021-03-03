<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services;

use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackEntity;
use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;
use webignition\BasilWorker\PersistenceBundle\Services\CallbackStateMutator;
use webignition\BasilWorker\PersistenceBundle\Tests\Functional\AbstractFunctionalTest;

class CallbackStateMutatorTest extends AbstractFunctionalTest
{
    private CallbackStateMutator $callbackStateMutator;

    protected function setUp(): void
    {
        parent::setUp();

        $callbackStateMutator = $this->container->get(CallbackStateMutator::class);
        if ($callbackStateMutator instanceof CallbackStateMutator) {
            $this->callbackStateMutator = $callbackStateMutator;
        }
    }

    /**
     * @dataProvider setQueuedDataProvider
     *
     * @param CallbackInterface::STATE_* $initialState
     * @param CallbackInterface::STATE_* $expectedState
     */
    public function testSetQueued(string $initialState, string $expectedState): void
    {
        foreach ($this->createCallbacks() as $callback) {
            $this->doSetAsStateTest(
                $callback,
                $initialState,
                $expectedState,
                function (CallbackInterface $callback) {
                    $this->callbackStateMutator->setQueued($callback);
                }
            );
        }
    }

    /**
     * @return array[]
     */
    public function setQueuedDataProvider(): array
    {
        return [
            CallbackInterface::STATE_AWAITING => [
                'initialState' => CallbackInterface::STATE_AWAITING,
                'expectedState' => CallbackInterface::STATE_QUEUED,
            ],
            CallbackInterface::STATE_QUEUED => [
                'initialState' => CallbackInterface::STATE_QUEUED,
                'expectedState' => CallbackInterface::STATE_QUEUED,
            ],
            CallbackInterface::STATE_SENDING => [
                'initialState' => CallbackInterface::STATE_SENDING,
                'expectedState' => CallbackInterface::STATE_QUEUED,
            ],
            CallbackInterface::STATE_FAILED => [
                'initialState' => CallbackInterface::STATE_FAILED,
                'expectedState' => CallbackInterface::STATE_FAILED,
            ],
            CallbackInterface::STATE_COMPLETE => [
                'initialState' => CallbackInterface::STATE_COMPLETE,
                'expectedState' => CallbackInterface::STATE_COMPLETE,
            ],
        ];
    }

    /**
     * @dataProvider setSendingDataProvider
     *
     * @param CallbackInterface::STATE_* $initialState
     * @param CallbackInterface::STATE_* $expectedState
     */
    public function testSetSending(string $initialState, string $expectedState): void
    {
        foreach ($this->createCallbacks() as $callback) {
            $this->doSetAsStateTest(
                $callback,
                $initialState,
                $expectedState,
                function (CallbackInterface $callback) {
                    $this->callbackStateMutator->setSending($callback);
                }
            );
        }
    }

    /**
     * @return array[]
     */
    public function setSendingDataProvider(): array
    {
        return [
            CallbackInterface::STATE_AWAITING => [
                'initialState' => CallbackInterface::STATE_AWAITING,
                'expectedState' => CallbackInterface::STATE_AWAITING,
            ],
            CallbackInterface::STATE_QUEUED => [
                'initialState' => CallbackInterface::STATE_QUEUED,
                'expectedState' => CallbackInterface::STATE_SENDING,
            ],
            CallbackInterface::STATE_SENDING => [
                'initialState' => CallbackInterface::STATE_SENDING,
                'expectedState' => CallbackInterface::STATE_SENDING,
            ],
            CallbackInterface::STATE_FAILED => [
                'initialState' => CallbackInterface::STATE_FAILED,
                'expectedState' => CallbackInterface::STATE_FAILED,
            ],
            CallbackInterface::STATE_COMPLETE => [
                'initialState' => CallbackInterface::STATE_COMPLETE,
                'expectedState' => CallbackInterface::STATE_COMPLETE,
            ],
        ];
    }

    /**
     * @dataProvider setFailedDataProvider
     *
     * @param CallbackInterface::STATE_* $initialState
     * @param CallbackInterface::STATE_* $expectedState
     */
    public function testSetFailed(string $initialState, string $expectedState): void
    {
        foreach ($this->createCallbacks() as $callback) {
            $this->doSetAsStateTest(
                $callback,
                $initialState,
                $expectedState,
                function (CallbackInterface $callback) {
                    $this->callbackStateMutator->setFailed($callback);
                }
            );
        }
    }

    /**
     * @return array[]
     */
    public function setFailedDataProvider(): array
    {
        return [
            CallbackInterface::STATE_AWAITING => [
                'initialState' => CallbackInterface::STATE_AWAITING,
                'expectedState' => CallbackInterface::STATE_AWAITING,
            ],
            CallbackInterface::STATE_QUEUED => [
                'initialState' => CallbackInterface::STATE_QUEUED,
                'expectedState' => CallbackInterface::STATE_QUEUED,
            ],
            CallbackInterface::STATE_SENDING => [
                'initialState' => CallbackInterface::STATE_SENDING,
                'expectedState' => CallbackInterface::STATE_FAILED,
            ],
            CallbackInterface::STATE_FAILED => [
                'initialState' => CallbackInterface::STATE_FAILED,
                'expectedState' => CallbackInterface::STATE_FAILED,
            ],
            CallbackInterface::STATE_COMPLETE => [
                'initialState' => CallbackInterface::STATE_COMPLETE,
                'expectedState' => CallbackInterface::STATE_COMPLETE,
            ],
        ];
    }

    /**
     * @dataProvider setCompleteDataProvider
     *
     * @param CallbackInterface::STATE_* $initialState
     * @param CallbackInterface::STATE_* $expectedState
     */
    public function testSetComplete(string $initialState, string $expectedState): void
    {
        foreach ($this->createCallbacks() as $callback) {
            $this->doSetAsStateTest(
                $callback,
                $initialState,
                $expectedState,
                function (CallbackInterface $callback) {
                    $this->callbackStateMutator->setComplete($callback);
                }
            );
        }
    }

    /**
     * @return array[]
     */
    public function setCompleteDataProvider(): array
    {
        return [
            CallbackInterface::STATE_AWAITING => [
                'initialState' => CallbackInterface::STATE_AWAITING,
                'expectedState' => CallbackInterface::STATE_AWAITING,
            ],
            CallbackInterface::STATE_QUEUED => [
                'initialState' => CallbackInterface::STATE_QUEUED,
                'expectedState' => CallbackInterface::STATE_QUEUED,
            ],
            CallbackInterface::STATE_SENDING => [
                'initialState' => CallbackInterface::STATE_SENDING,
                'expectedState' => CallbackInterface::STATE_COMPLETE,
            ],
            CallbackInterface::STATE_FAILED => [
                'initialState' => CallbackInterface::STATE_FAILED,
                'expectedState' => CallbackInterface::STATE_FAILED,
            ],
            CallbackInterface::STATE_COMPLETE => [
                'initialState' => CallbackInterface::STATE_COMPLETE,
                'expectedState' => CallbackInterface::STATE_COMPLETE,
            ],
        ];
    }

    /**
     * @dataProvider setSendingDataProvider
     *
     * @param CallbackInterface::STATE_* $initialState
     * @param CallbackInterface::STATE_* $expectedState
     */
    private function doSetAsStateTest(
        CallbackInterface $callback,
        string $initialState,
        string $expectedState,
        callable $setter
    ): void {
        $callback->setState($initialState);

        $this->entityManager->persist($callback);
        $this->entityManager->flush();

        self::assertSame($initialState, $callback->getState());

        $setter($callback);

        self::assertSame($expectedState, $callback->getState());
    }

    /**
     * @return CallbackInterface[]
     */
    private function createCallbacks(): array
    {
        return [
            'default entity' => $this->createCallbackEntity(),
        ];
    }

    private function createCallbackEntity(): CallbackEntity
    {
        return CallbackEntity::create(CallbackInterface::TYPE_COMPILATION_FAILED, []);
    }
}
