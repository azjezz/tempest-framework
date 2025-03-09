<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\AlphaNumeric;

/**
 * @internal
 */
final class AlphaNumericTest extends TestCase
{
    public function test_alphanumeric(): void
    {
        $rule = new AlphaNumeric();

        static::assertSame('Value should only contain alphanumeric characters', $rule->message());
        static::assertFalse($rule->isValid('string_123'));
        static::assertTrue($rule->isValid('string123'));
        static::assertTrue($rule->isValid('STRING123'));
    }
}
