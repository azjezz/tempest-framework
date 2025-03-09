<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\Uppercase;

/**
 * @internal
 */
final class UppercaseTest extends TestCase
{
    public function test_uppercase(): void
    {
        $rule = new Uppercase();

        static::assertSame('Value should be an uppercase string', $rule->message());

        static::assertTrue($rule->isValid('ABC'));
        static::assertTrue($rule->isValid('ÀBÇ'));
        static::assertFalse($rule->isValid('abc'));
        static::assertFalse($rule->isValid('àbç'));
        static::assertFalse($rule->isValid('AbC'));
    }
}
