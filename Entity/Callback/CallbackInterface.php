<?php

declare(strict_types=1);

namespace webignition\BasilWorker\PersistenceBundle\Entity\Callback;

use webignition\BasilWorker\PersistenceBundle\Entity\EntityInterface;

interface CallbackInterface extends EntityInterface
{
    public const STATE_AWAITING = 'awaiting';
    public const STATE_QUEUED = 'queued';
    public const STATE_SENDING = 'sending';
    public const STATE_FAILED = 'failed';
    public const STATE_COMPLETE = 'complete';

    public const TYPE_COMPILE_FAILURE = 'compile-failure';
    public const TYPE_COMPILE_SUCCESS = 'compile-success';
    public const TYPE_EXECUTE_DOCUMENT_RECEIVED = 'execute-document-received';
    public const TYPE_JOB_TIMEOUT = 'job-timeout';
    public const TYPE_JOB_COMPLETE = 'job-complete';

    public const TYPE_FINISHED_COMPILATION_FAILED = 'finished-compilation-failed';
    public const TYPE_FINISHED_JOB_TIMEOUT = 'finished-job-timeout';
    public const TYPE_FINISHED_JOB_COMPLETE = 'finished-job-complete';
    public const TYPE_FINISHED_TEST_FAILURE = 'finished-test-failed';
    public const TYPE_TEST_PROGRESS_STARTED = 'test-progress-started';
    public const TYPE_TEST_PROGRESS_FINISHED = 'test-progress-finished';

    public const TYPE_JOB_STARTED = 'job/started';
    public const TYPE_JOB_TIME_OUT = 'job/timed-out';
    public const TYPE_JOB_COMPLETED = 'job/completed';
    public const TYPE_JOB_FAILED = 'job/failed';
    public const TYPE_COMPILATION_STARTED = 'compilation/started';
    public const TYPE_COMPILATION_SUCCEEDED = 'compilation/completed';
    public const TYPE_COMPILATION_FAILED = 'compilation/failed';
    public const TYPE_EXECUTION_STARTED = 'execution/started';
    public const TYPE_EXECUTION_COMPLETED = 'execution/completed';
    public const TYPE_TEST_STARTED = 'test/started';
    public const TYPE_TEST_FINISHED = 'test/finished';
    public const TYPE_STEP_STARTED = 'step/started';
    public const TYPE_STEP_PASSED = 'step/passed';
    public const TYPE_STEP_FAILED = 'step/failed';

    public function getEntity(): CallbackEntity;

    /**
     * @return CallbackInterface::STATE_*
     */
    public function getState(): string;

    /**
     * @param CallbackInterface::STATE_* $state
     */
    public function setState(string $state): void;

    public function getRetryCount(): int;
    public function getType(): string;

    /**
     * @return array<mixed>
     */
    public function getPayload(): array;
    public function incrementRetryCount(): void;
    public function hasReachedRetryLimit(int $limit): bool;
}
