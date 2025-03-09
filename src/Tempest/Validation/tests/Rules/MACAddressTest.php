<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\MACAddress;

/**
 * @internal
 */
final class MACAddressTest extends TestCase
{
    public function test_ip_address(): void
    {
        $rule = new MACAddress();

        static::assertSame('Value should be a valid MAC Address', $rule->message());
        static::assertTrue($rule->isValid('00:1A:2B:3C:4D:5E'));
        static::assertTrue($rule->isValid('01-23-45-67-89-AB'));
        static::assertTrue($rule->isValid('A1:B2:C3:D4:E5:F6'));
        static::assertTrue($rule->isValid('a1:b2:c3:d4:e5:f6'));
        static::assertTrue($rule->isValid('FF:FF:FF:FF:FF:FF'));

        static::assertFalse($rule->isValid('00:1A:2B:3C:4D'));
        static::assertFalse($rule->isValid('01-23-45-67-89-AB-CD'));
        static::assertFalse($rule->isValid('A1:B2:C3:D4:E5:G6'));
        static::assertFalse($rule->isValid('a1:b2:c3:d4:e5:f6:7'));
        static::assertFalse($rule->isValid('FF:FF:FF:FF:FF'));
    }
}
