<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use webignition\BasilWorker\PersistenceBundle\Entity\Callback\CallbackInterface;
use webignition\BasilWorker\PersistenceBundle\Entity\Test;
use webignition\BasilWorker\PersistenceBundle\Entity\TestConfiguration;

class TestTest extends TestCase
{
    public function testHasState(): void
    {
        $test = Test::create(
            \Mockery::mock(TestConfiguration::class),
            '',
            '',
            0,
            0
        );

        self::assertTrue($test->hasState(Test::STATE_AWAITING));
        self::assertFalse($test->hasState(Test::STATE_COMPLETE));

        $test->setState(CallbackInterface::STATE_COMPLETE);
        self::assertFalse($test->hasState(Test::STATE_AWAITING));
        self::assertTrue($test->hasState(Test::STATE_COMPLETE));
    }
}
