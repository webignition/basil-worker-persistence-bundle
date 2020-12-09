<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Persister;

use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;

class CallbackPersister extends AbstractObjectPersister
{
    public function persist(CallbackInterface $callback): CallbackInterface
    {
        $this->doPersist($callback->getEntity());

        return $callback;
    }
}
