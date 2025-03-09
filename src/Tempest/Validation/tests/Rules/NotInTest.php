<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\NotIn;

/**
 * @internal
 */
final class NotInTest extends TestCase
{
    public function test_it_works(): void
    {
        $rule = new NotIn([4, 2, 0]);

        static::assertFalse($rule->isValid(4));
        static::assertFalse($rule->isValid(2));
        static::assertFalse($rule->isValid(0));

        static::assertTrue($rule->isValid(1));
        static::assertTrue($rule->isValid(3));

        static::assertSame('Value cannot be any of: 4, 2, 0', $rule->message());
    }
}
