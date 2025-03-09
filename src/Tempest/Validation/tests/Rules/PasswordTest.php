<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\Password;

/**
 * @internal
 */
final class PasswordTest extends TestCase
{
    public function test_defaults(): void
    {
        $rule = new Password();

        static::assertTrue($rule->isValid('123456789012'));
        static::assertTrue($rule->isValid('aaaaaaaaaaaa'));
    }

    public function test_invalid_input(): void
    {
        $rule = new Password();
        static::assertFalse($rule->isValid(123456789012));
        static::assertFalse($rule->isValid([123456789012]));
    }

    public function test_minimum(): void
    {
        $rule = new Password(min: 4);
        static::assertTrue($rule->isValid('12345'));
        static::assertTrue($rule->isValid('1234'));
        static::assertFalse($rule->isValid('123'));
    }

    public function test_mixed_case(): void
    {
        $rule = new Password(mixedCase: true);
        static::assertTrue($rule->isValid('abcdEFGHIJKL'));
        static::assertFalse($rule->isValid('abcdefghijkl'));
        static::assertFalse($rule->isValid('ABCDEFGHIJKL'));
    }

    public function test_letters(): void
    {
        $rule = new Password(letters: true);
        static::assertTrue($rule->isValid('12345678901a'));
        static::assertFalse($rule->isValid('123456789012'));
    }

    public function test_numbers(): void
    {
        $rule = new Password(numbers: true);
        static::assertTrue($rule->isValid('123456789012'));
        static::assertTrue($rule->isValid('1aaaaaaaaaaa'));
        static::assertFalse($rule->isValid('abcdefghijkl'));
    }

    public function test_symbols(): void
    {
        $rule = new Password(symbols: true);
        static::assertTrue($rule->isValid('123456789012@'));
        static::assertFalse($rule->isValid('123456789012'));
    }

    public function test_message(): void
    {
        $rule = new Password();
        static::assertSame('Value should contain at least 12 characters', $rule->message()[0]);

        $rule = new Password(min: 4);
        static::assertSame('Value should contain at least 4 characters', $rule->message()[0]);

        $rule = new Password(mixedCase: true);
        static::assertSame('Value should contain at least 12 characters', $rule->message()[0]);
        static::assertSame('at least one uppercase and one lowercase letter', $rule->message()[1]);

        $rule = new Password(letters: true);
        static::assertSame('Value should contain at least 12 characters', $rule->message()[0]);
        static::assertSame('at least one letter', $rule->message()[1]);

        $rule = new Password(numbers: true);
        static::assertSame('Value should contain at least 12 characters', $rule->message()[0]);
        static::assertSame('at least one number', $rule->message()[1]);

        $rule = new Password(symbols: true);
        static::assertSame('Value should contain at least 12 characters', $rule->message()[0]);
        static::assertSame('at least one symbol', $rule->message()[1]);

        $rule = new Password(min: 4, mixedCase: true, letters: true, numbers: true, symbols: true);
        static::assertSame('Value should contain at least 4 characters', $rule->message()[0]);
        static::assertSame('at least one uppercase and one lowercase letter', $rule->message()[1]);
        static::assertSame('at least one number', $rule->message()[2]);
        static::assertSame('at least one letter', $rule->message()[3]);
        static::assertSame('at least one symbol', $rule->message()[4]);
    }
}
