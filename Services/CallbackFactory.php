<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services;

use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackEntity;
use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;
use webignition\BasilWorker\PersistenceBundle\Services\Persister\CallbackPersister;

class CallbackFactory
{
    private CallbackPersister $callbackPersister;

    public function __construct(CallbackPersister $callbackPersister)
    {
        $this->callbackPersister = $callbackPersister;
    }

    /**
     * @param CallbackInterface::TYPE_* $type
     * @param array<mixed> $payload
     */
    public function create(string $type, array $payload): CallbackInterface
    {
        return $this->callbackPersister->persist(CallbackEntity::create($type, $payload));
    }
}
