<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\Alpha;

/**
 * @internal
 */
final class AlphaTest extends TestCase
{
    public function test_alpha(): void
    {
        $rule = new Alpha();

        static::assertSame('Value should only contain alphabetic characters', $rule->message());
        static::assertFalse($rule->isValid('string123'));
        static::assertTrue($rule->isValid('string'));
        static::assertTrue($rule->isValid('STRING'));
    }
}
