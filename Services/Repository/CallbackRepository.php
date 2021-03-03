<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Services\Repository;

use Doctrine\ORM\EntityManagerInterface;
use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackEntity;

/**
 * @extends AbstractEntityRepository<CallbackEntity>
 */
class CallbackRepository extends AbstractEntityRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, CallbackEntity::class);
    }

    public function hasForType(string $type): bool
    {
        return $this->count(['type' => $type]) > 0;
    }
}
