<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\StartsWith;

/**
 * @internal
 */
final class StartsWithTest extends TestCase
{
    public function test_starts_with(): void
    {
        $rule = new StartsWith(needle: 'ab');

        static::assertSame('Value should start with ab', $rule->message());

        static::assertTrue($rule->isValid('ab'));
        static::assertTrue($rule->isValid('abc'));
        static::assertFalse($rule->isValid('a'));
        static::assertFalse($rule->isValid('3434'));
    }
}
