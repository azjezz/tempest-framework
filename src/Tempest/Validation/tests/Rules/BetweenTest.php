<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\Between;

/**
 * @internal
 */
final class BetweenTest extends TestCase
{
    public function test_between(): void
    {
        $rule = new Between(min: 0, max: 10);

        static::assertSame('Value should be between 0 and 10', $rule->message());

        static::assertTrue($rule->isValid(0));
        static::assertTrue($rule->isValid(10));
        static::assertTrue($rule->isValid(5));
        static::assertFalse($rule->isValid(11));
        static::assertFalse($rule->isValid(-1));
    }
}
