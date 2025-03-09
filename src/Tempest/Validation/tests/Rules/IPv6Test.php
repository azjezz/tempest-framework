<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\IPv6;

/**
 * @internal
 */
final class IPv6Test extends TestCase
{
    public function test_ipv6_address(): void
    {
        $rule = new IPv6();

        static::assertSame('Value should be a valid IPv6 address', $rule->message());

        static::assertTrue($rule->isValid('2001:0db8:85a3:0000:0000:8a2e:0370:7334'));
        static::assertTrue($rule->isValid('2a03:b0c0:3:d0::11f5:3001'));

        static::assertFalse($rule->isValid('192.168.0.1'));
        static::assertFalse($rule->isValid('10.0.0.1'));
        static::assertFalse($rule->isValid('172.16.0.1'));
    }

    public function test_ip_address_without_private_range(): void
    {
        $rule = new IPv6(allowPrivateRange: false);
        static::assertFalse($rule->isValid('fd36:ecf4:e32b:5e21:aaaa:aaaa:aaaa:aaaa'));
        static::assertTrue($rule->isValid('2a03:b0c0:3:d0::11f5:3001'));

        $rule = new IPv6(allowPrivateRange: true);
        static::assertTrue($rule->isValid('fd36:ecf4:e32b:5e21:aaaa:aaaa:aaaa:aaaa'));
        static::assertTrue($rule->isValid('2a03:b0c0:3:d0::11f5:3001'));
    }

    public function test_ip_address_without_reserved_range(): void
    {
        $rule = new IPv6(allowReservedRange: false);
        static::assertFalse($rule->isValid('::1'));
        static::assertTrue($rule->isValid('2a03:b0c0:3:d0::11f5:3001'));

        $rule = new IPv6(allowReservedRange: true);
        static::assertTrue($rule->isValid('2001:db8:ffff:ffff:ffff:ffff:ffff:ffff'));
        static::assertTrue($rule->isValid('2a03:b0c0:3:d0::11f5:3001'));
    }

    public function test_messages(): void
    {
        $rule = new IPv6();
        static::assertSame('Value should be a valid IPv6 address', $rule->message());

        $rule = new IPv6(allowPrivateRange: false);
        static::assertSame('Value should be a valid IPv6 address that is not in a private range', $rule->message());

        $rule = new IPv6(allowReservedRange: false);
        static::assertSame('Value should be a valid IPv6 address that is not in a reserved range', $rule->message());

        $rule = new IPv6(allowPrivateRange: false, allowReservedRange: false);
        static::assertSame('Value should be a valid IPv6 address that is not in a private range and not in a reserved range', $rule->message());
    }
}
