<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services;

use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackEntity;
use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;

class CallbackFactory
{
    private CallbackStore $callbackStore;

    public function __construct(CallbackStore $callbackStore)
    {
        $this->callbackStore = $callbackStore;
    }

    /**
     * @param CallbackInterface::TYPE_* $type
     * @param array<mixed> $payload
     */
    public function create(string $type, array $payload): CallbackInterface
    {
        return $this->callbackStore->store(CallbackEntity::create($type, $payload));
    }
}
