<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\NotEmpty;

/**
 * @internal
 */
final class NotEmptyTest extends TestCase
{
    public function test_not_empty(): void
    {
        $rule = new NotEmpty();

        static::assertTrue($rule->isValid('t'));
        static::assertFalse($rule->isValid(''));
        static::assertFalse($rule->isValid(1));
    }
}
