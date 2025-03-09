<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\EndsWith;

/**
 * @internal
 */
final class EndsWithTest extends TestCase
{
    public function test_ends_with(): void
    {
        $rule = new EndsWith(needle: 'ab');

        static::assertSame('Value should end with ab', $rule->message());

        static::assertTrue($rule->isValid('ab'));
        static::assertTrue($rule->isValid('cab'));
        static::assertFalse($rule->isValid('b'));
        static::assertFalse($rule->isValid('3434'));
    }
}
