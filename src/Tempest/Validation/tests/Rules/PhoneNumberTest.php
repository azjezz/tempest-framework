<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\PhoneNumber;

/**
 * @internal
 */
final class PhoneNumberTest extends TestCase
{
    public function test_phone_number(): void
    {
        $rule = new PhoneNumber();

        static::assertSame('Value should be a valid phone number', $rule->message());

        static::assertFalse($rule->isValid('this is not a phone number'));
        static::assertFalse($rule->isValid('john.doe@example.com'));
        static::assertTrue($rule->isValid('+1 (805) 380-4329'));
        static::assertTrue($rule->isValid('+32 0497 88 93 11'));

        $rule = new PhoneNumber('US');

        static::assertSame('Value should be a valid US phone number', $rule->message());
        static::assertTrue($rule->isValid('(805) 380-4329'));

        $rule = new PhoneNumber('BE');

        static::assertSame('Value should be a valid BE phone number', $rule->message());
        static::assertTrue($rule->isValid('0497 88 93 11'));
    }
}
