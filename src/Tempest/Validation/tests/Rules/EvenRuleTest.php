<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\Even;

/**
 * @internal
 */
final class EvenRuleTest extends TestCase
{
    public function test_it_works(): void
    {
        $rule = new Even();

        static::assertTrue($rule->isValid(4));
        static::assertTrue($rule->isValid(2));
        static::assertTrue($rule->isValid(0));

        static::assertFalse($rule->isValid(1));
        static::assertFalse($rule->isValid(3));

        static::assertSame('Value should be an even number', $rule->message());
    }
}
