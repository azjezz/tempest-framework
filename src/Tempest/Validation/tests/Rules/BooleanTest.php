<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\Boolean;

/**
 * @internal
 */
final class BooleanTest extends TestCase
{
    public function test_boolean(): void
    {
        $rule = new Boolean();

        static::assertTrue($rule->isValid(true));
        static::assertTrue($rule->isValid('true'));
        static::assertTrue($rule->isValid(1));
        static::assertTrue($rule->isValid('1'));
        static::assertTrue($rule->isValid(false));
        static::assertTrue($rule->isValid('false'));
        static::assertTrue($rule->isValid(0));
        static::assertTrue($rule->isValid('0'));
        static::assertFalse($rule->isValid(5));
        static::assertFalse($rule->isValid(2.5));
        static::assertFalse($rule->isValid('string'));
    }

    public function test_boolean_message(): void
    {
        $rule = new Boolean();

        static::assertSame('Value should represent a boolean value', $rule->message());
    }
}
