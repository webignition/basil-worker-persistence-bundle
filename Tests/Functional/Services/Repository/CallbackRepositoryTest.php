<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Functional\Services\Repository;

use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackEntity;
use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;
use webignition\BasilWorker\PersistenceBundle\Entity\EntityInterface;
use webignition\BasilWorker\PersistenceBundle\Services\Repository\CallbackRepository;

/**
 * @extends AbstractEntityRepositoryTest<CallbackEntity>
 */
class CallbackRepositoryTest extends AbstractEntityRepositoryTest
{
    protected function getRepository(): ?CallbackRepository
    {
        $repository = $this->container->get(CallbackRepository::class);
        if ($repository instanceof CallbackRepository) {
            return $repository;
        }

        return null;
    }

    protected function createSingleEntity(): EntityInterface
    {
        return CallbackEntity::create(CallbackInterface::TYPE_COMPILATION_FAILED, []);
    }

    protected function createEntityCollection(): array
    {
        $callback0 = CallbackEntity::create(CallbackInterface::TYPE_COMPILATION_FAILED, []);
        $callback0->setState(CallbackInterface::STATE_AWAITING);

        $callback1 = CallbackEntity::create(CallbackInterface::TYPE_TEST_STARTED, []);
        $callback1->setState(CallbackInterface::STATE_AWAITING);

        $callback2 = CallbackEntity::create(CallbackInterface::TYPE_JOB_TIME_OUT, []);
        $callback2->setState(CallbackInterface::STATE_COMPLETE);

        return [
            $callback0,
            $callback1,
            $callback2,
        ];
    }

    public function findOneByDataProvider(): array
    {
        return [
            'state awaiting' => [
                'criteria' => [
                    'state' => CallbackInterface::STATE_AWAITING,
                ],
                'orderBy' => null,
                'expectedEntityIndex' => 0,
            ],
            'type compile failure' => [
                'criteria' => [
                    'type' => CallbackInterface::TYPE_COMPILATION_FAILED,
                ],
                'orderBy' => null,
                'expectedEntityIndex' => 0,
            ],
            'type execute document received' => [
                'criteria' => [
                    'type' => CallbackInterface::TYPE_TEST_STARTED,
                ],
                'orderBy' => null,
                'expectedEntityIndex' => 1,
            ],
            'state awaiting and type execute document received' => [
                'criteria' => [
                    'state' => CallbackInterface::STATE_AWAITING,
                    'type' => CallbackInterface::TYPE_TEST_STARTED,
                ],
                'orderBy' => null,
                'expectedEntityIndex' => 1,
            ],
            'type job timeout' => [
                'criteria' => [
                    'type' => CallbackInterface::TYPE_JOB_TIME_OUT,
                ],
                'orderBy' => null,
                'expectedEntityIndex' => 2,
            ],
            'invalid type' => [
                'criteria' => [
                    'type' => 'Invalid',
                ],
                'orderBy' => null,
                'expectedEntityIndex' => null,
            ],
        ];
    }

    public function countDataProvider(): array
    {
        return [
            'state awaiting' => [
                'criteria' => [
                    'state' => CallbackInterface::STATE_AWAITING,
                ],
                'expectedCount' => 2,
            ],
            'type compile failure' => [
                'criteria' => [
                    'type' => CallbackInterface::TYPE_COMPILATION_FAILED,
                ],
                'expectedCount' => 1,
            ],
            'type execute document received' => [
                'criteria' => [
                    'type' => CallbackInterface::TYPE_TEST_STARTED,
                ],
                'expectedCount' => 1,
            ],
            'state awaiting and type execute document received' => [
                'criteria' => [
                    'state' => CallbackInterface::STATE_AWAITING,
                    'type' => CallbackInterface::TYPE_TEST_STARTED,
                ],
                'expectedCount' => 1,
            ],
            'type job timeout' => [
                'criteria' => [
                    'type' => CallbackInterface::TYPE_JOB_TIME_OUT,
                ],
                'expectedCount' => 1,
            ],
            'invalid type' => [
                'criteria' => [
                    'type' => 'Invalid',
                ],
                'expectedCount' => 0,
            ],
        ];
    }
}
