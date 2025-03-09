<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use DateTimeZone;
use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\Timezone;

/**
 * @internal
 */
final class TimezoneTest extends TestCase
{
    public function test_timezone(): void
    {
        $rule = new Timezone();

        static::assertSame('Value should be a valid timezone', $rule->message());

        static::assertFalse($rule->isValid('invalid_timezone'));
        static::assertFalse($rule->isValid('Asia/Sydney'));
        static::assertTrue($rule->isValid('America/New_York'));
        static::assertTrue($rule->isValid('Europe/London'));
        static::assertTrue($rule->isValid('Europe/Paris'));
        static::assertTrue($rule->isValid('UTC'));
    }

    public function test_timezone_with_country_code(): void
    {
        $rule = new Timezone(DateTimeZone::PER_COUNTRY, 'AU');

        static::assertFalse($rule->isValid('America/New_York'));
        static::assertTrue($rule->isValid('Australia/Sydney'));
        static::assertTrue($rule->isValid('Australia/Melbourne'));

        $rule = new Timezone(DateTimeZone::PER_COUNTRY, 'US');

        static::assertFalse($rule->isValid('Europe/Paris'));
        static::assertTrue($rule->isValid('America/New_York'));
        static::assertTrue($rule->isValid('America/Los_Angeles'));
        static::assertTrue($rule->isValid('America/Chicago'));
    }

    public function test_timezone_with_group(): void
    {
        $rule = new Timezone(DateTimeZone::ASIA);

        static::assertFalse($rule->isValid('Africa/Nairobi'));
        static::assertTrue($rule->isValid('Asia/Tokyo'));
        static::assertTrue($rule->isValid('Asia/Hong_Kong'));
        static::assertTrue($rule->isValid('Asia/Singapore'));

        $rule = new Timezone(DateTimeZone::INDIAN);

        static::assertFalse($rule->isValid('Europe/Paris'));
        static::assertTrue($rule->isValid('Indian/Reunion'));
        static::assertTrue($rule->isValid('Indian/Comoro'));
    }
}
