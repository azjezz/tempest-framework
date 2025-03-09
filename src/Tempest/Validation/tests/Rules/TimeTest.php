<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\Time;

/**
 * @internal
 */
final class TimeTest extends TestCase
{
    public function test_time(): void
    {
        $rule = new Time();

        static::assertSame('Value should be a valid time in the format of hh:mm xm', $rule->message());

        static::assertFalse($rule->isValid('0001'));
        static::assertFalse($rule->isValid('01:00'));
        static::assertFalse($rule->isValid('200'));
        static::assertFalse($rule->isValid('01:60 a.m.'));
        static::assertFalse($rule->isValid('23:00'));
        static::assertFalse($rule->isValid('2300'));

        static::assertTrue($rule->isValid('01:00 am'));
        static::assertTrue($rule->isValid('01:00 a.m.'));
        static::assertTrue($rule->isValid('01:00 A.M.'));
        static::assertTrue($rule->isValid('01:00 AM'));
        static::assertTrue($rule->isValid('01:00 pm'));
        static::assertTrue($rule->isValid('01:00 p.m.'));
        static::assertTrue($rule->isValid('01:00 P.M.'));
        static::assertTrue($rule->isValid('01:00 PM'));
        static::assertTrue($rule->isValid('01:59 a.m.'));
    }

    public function test_military_time(): void
    {
        $rule = new Time(twentyFourHour: true);

        static::assertSame('Value should be a valid time in the 24-hour format of hh:mm', $rule->message());

        static::assertFalse($rule->isValid('2400'));
        static::assertFalse($rule->isValid('01:00 am'));
        static::assertFalse($rule->isValid('01:00 a.m.'));
        static::assertFalse($rule->isValid('01:00 A.M.'));
        static::assertFalse($rule->isValid('01:00 AM'));
        static::assertFalse($rule->isValid('01:00 pm'));
        static::assertFalse($rule->isValid('01:00 p.m.'));
        static::assertFalse($rule->isValid('01:00 P.M.'));
        static::assertFalse($rule->isValid('01:00 PM'));
        static::assertFalse($rule->isValid('01:59 a.m.'));
        static::assertFalse($rule->isValid('24:00'));

        static::assertTrue($rule->isValid('23:00'));
        static::assertTrue($rule->isValid('2300'));
        static::assertTrue($rule->isValid('0100'));
        static::assertTrue($rule->isValid('0200'));
        static::assertTrue($rule->isValid('0300'));
        static::assertTrue($rule->isValid('0400'));
        static::assertTrue($rule->isValid('0500'));
        static::assertTrue($rule->isValid('0600'));
        static::assertTrue($rule->isValid('0700'));
        static::assertTrue($rule->isValid('0800'));
        static::assertTrue($rule->isValid('0900'));
        static::assertTrue($rule->isValid('1000'));
        static::assertTrue($rule->isValid('1100'));
        static::assertTrue($rule->isValid('1200'));
        static::assertTrue($rule->isValid('1300'));
        static::assertTrue($rule->isValid('1400'));
        static::assertTrue($rule->isValid('1500'));
        static::assertTrue($rule->isValid('1600'));
        static::assertTrue($rule->isValid('1700'));
        static::assertTrue($rule->isValid('1800'));
        static::assertTrue($rule->isValid('1900'));
        static::assertTrue($rule->isValid('2000'));
        static::assertTrue($rule->isValid('2100'));
        static::assertTrue($rule->isValid('2200'));
        static::assertTrue($rule->isValid('2300'));
        static::assertTrue($rule->isValid('2340'));
    }
}
