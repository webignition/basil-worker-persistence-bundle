<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackEntity;
use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;

class CallbackEntityTest extends TestCase
{
    public function testIncrementRetryCount(): void
    {
        $callback = CallbackEntity::create(CallbackInterface::TYPE_COMPILATION_FAILED, []);
        self::assertSame(0, $callback->getRetryCount());

        $callback->incrementRetryCount();
        self::assertSame(1, $callback->getRetryCount());

        $callback->incrementRetryCount();
        self::assertSame(2, $callback->getRetryCount());

        $callback->incrementRetryCount();
        self::assertSame(3, $callback->getRetryCount());
    }
}
