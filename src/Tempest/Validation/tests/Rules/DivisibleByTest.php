<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\DivisibleBy;

/**
 * @internal
 */
final class DivisibleByTest extends TestCase
{
    public function test_it_works(): void
    {
        $rule = new DivisibleBy(5);

        static::assertTrue($rule->isValid(10));
        static::assertTrue($rule->isValid(5));
        static::assertFalse($rule->isValid(0));

        static::assertFalse($rule->isValid(3));
        static::assertFalse($rule->isValid(4));
        static::assertFalse($rule->isValid(6));

        static::assertSame('Value should be divisible by 5', $rule->message());
    }
}
