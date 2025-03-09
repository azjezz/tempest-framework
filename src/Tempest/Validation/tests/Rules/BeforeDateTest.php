<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\BeforeDate;

/**
 * @internal
 */
final class BeforeDateTest extends TestCase
{
    public function test_it_works_without_inclusive_param(): void
    {
        $date = new DateTimeImmutable();
        $rule = new BeforeDate($date);

        static::assertSame('Value must be a date before ' . $date->format('Y-m-d H:i:s'), $rule->message());

        static::assertFalse($rule->isValid($date->modify('+1 minute')));
        static::assertTrue($rule->isValid($date->modify('-1 second')));
        static::assertTrue($rule->isValid($date));
    }

    public function test_it_works_with_inclusive_param(): void
    {
        $date = new DateTimeImmutable();
        $rule = new BeforeDate($date, inclusive: true);

        static::assertSame('Value must be a date before or equal to ' . $date->format('Y-m-d H:i:s'), $rule->message());

        static::assertFalse($rule->isValid($date->modify('+1 minute')));
        static::assertTrue($rule->isValid($date->modify('-1 second')));
        static::assertFalse($rule->isValid($date));
    }

    public function test_it_works_with_timezones(): void
    {
        // given we create a date in a specific timezone (UTC-5)
        $date = new DateTimeImmutable('now', new DateTimeZone('America/New_York'));
        $rule = new BeforeDate($date, inclusive: false);

        $utcDate = new DateTimeImmutable();

        // when we validate the date, it doesn't matter that timezones are different,
        // it'll compare the UTC timestamps
        static::assertTrue($utcDate->format('Y-m-d H:i:s') > $date->format('Y-m-d H:i:s'));
        static::assertFalse($rule->isValid($utcDate->modify('+1 minute')));
        static::assertTrue($rule->isValid($utcDate->modify('-1 minute')));
    }
}
