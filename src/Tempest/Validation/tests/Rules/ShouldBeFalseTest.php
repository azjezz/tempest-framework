<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\ShouldBeFalse;

/**
 * @internal
 */
final class ShouldBeFalseTest extends TestCase
{
    public function test_should_be_false(): void
    {
        $rule = new ShouldBeFalse();

        static::assertFalse($rule->isValid(true));
        static::assertFalse($rule->isValid('true'));
        static::assertFalse($rule->isValid(1));
        static::assertFalse($rule->isValid('1'));
        static::assertTrue($rule->isValid(false));
        static::assertTrue($rule->isValid('false'));
        static::assertTrue($rule->isValid(0));
        static::assertTrue($rule->isValid('0'));
    }

    public function test_should_be_false_message(): void
    {
        $rule = new ShouldBeFalse();

        static::assertSame('Value should represent a boolean false value.', $rule->message());
    }
}
