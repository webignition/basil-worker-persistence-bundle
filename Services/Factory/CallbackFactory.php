<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Factory;

use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackEntity;
use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;

class CallbackFactory extends AbstractFactory
{
    /**
     * @param CallbackInterface::TYPE_* $type
     * @param array<mixed>              $payload
     */
    public function create(string $type, array $payload): CallbackInterface
    {
        $callback = CallbackEntity::create($type, $payload);

        $this->persist($callback);

        return $callback;
    }
}
