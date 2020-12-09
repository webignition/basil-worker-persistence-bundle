<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Store;

use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;
use webignition\BasilWorker\PersistenceBundle\Services\Repository\CallbackRepository;

class CallbackStore
{
    private CallbackRepository $repository;

    public function __construct(CallbackRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getFinishedCount(): int
    {
        return $this->repository->count([
            'state' => [
                CallbackInterface::STATE_FAILED,
                CallbackInterface::STATE_COMPLETE,
            ],
        ]);
    }

    public function getCompileFailureTypeCount(): int
    {
        return $this->repository->count([
            'type' => [
                CallbackInterface::TYPE_COMPILE_FAILURE,
            ],
        ]);
    }

    public function getJobTimeoutTypeCount(): int
    {
        return $this->repository->count([
            'type' => [
                CallbackInterface::TYPE_JOB_TIMEOUT,
            ],
        ]);
    }
}
