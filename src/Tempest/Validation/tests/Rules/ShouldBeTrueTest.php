<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\ShouldBeTrue;

/**
 * @internal
 */
final class ShouldBeTrueTest extends TestCase
{
    public function test_should_be_true(): void
    {
        $rule = new ShouldBeTrue();

        static::assertFalse($rule->isValid(false));
        static::assertFalse($rule->isValid('false'));
        static::assertFalse($rule->isValid(0));
        static::assertFalse($rule->isValid('0'));
        static::assertTrue($rule->isValid(true));
        static::assertTrue($rule->isValid('true'));
        static::assertTrue($rule->isValid(1));
        static::assertTrue($rule->isValid('1'));
    }

    public function test_should_be_true_message(): void
    {
        $rule = new ShouldBeTrue();

        static::assertSame('Value should represent a boolean true value.', $rule->message());
    }
}
