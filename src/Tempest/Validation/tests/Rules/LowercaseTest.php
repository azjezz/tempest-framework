<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\Lowercase;

/**
 * @internal
 */
final class LowercaseTest extends TestCase
{
    public function test_lowercase(): void
    {
        $rule = new Lowercase();

        static::assertSame('Value should be a lowercase string', $rule->message());

        static::assertTrue($rule->isValid('abc'));
        static::assertTrue($rule->isValid('àbç'));
        static::assertFalse($rule->isValid('ABC'));
        static::assertFalse($rule->isValid('ÀBÇ'));
        static::assertFalse($rule->isValid('AbC'));
    }
}
