<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\Timestamp;

/**
 * @internal
 */
final class TimestampTest extends TestCase
{
    public function test_timestamp(): void
    {
        $rule = new Timestamp();

        static::assertTrue($rule->isValid(time()));
        static::assertFalse($rule->isValid('2021-01-01'));
    }

    public function test_timestamp_message(): void
    {
        $rule = new Timestamp();

        static::assertSame('Value should be a valid timestamp', $rule->message());
    }
}
