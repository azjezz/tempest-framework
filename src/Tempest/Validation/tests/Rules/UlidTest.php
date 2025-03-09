<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\Ulid;

/**
 * @internal
 */
final class UlidTest extends TestCase
{
    public function test_ulid(): void
    {
        $rule = new Ulid();

        static::assertSame('Value should be a valid ULID', $rule->message());

        static::assertTrue($rule->isValid('01FV8CE8P3XVZTVK0S6F05Z5ZA'));
        static::assertTrue($rule->isValid('01fv8ce8p3xvztvk0S6f05z5za'));
        static::assertFalse($rule->isValid('01FV8CE8P3XVZTVK0S6F05Z5ZU')); // contains invalid character
        static::assertFalse($rule->isValid('01FV8CE8P3XVZTVK0S6F05')); // too short
        static::assertFalse($rule->isValid('01FV8CE8P3XVZTVK0S6F05Z5ZAAAAA')); // too long
    }
}
