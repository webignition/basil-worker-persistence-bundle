<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Store;

use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;
use webignition\BasilWorker\PersistenceBundle\Services\Repository\CallbackRepository;

class CallbackStore
{
    public function __construct(private CallbackRepository $repository)
    {
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

    /**
     * @param CallbackInterface::TYPE_* $type
     */
    public function getTypeCount(string $type): int
    {
        return $this->repository->count([
            'type' => $type,
        ]);
    }
}
