<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\ArrayList;

/**
 * @internal
 */
final class ArrayListTest extends TestCase
{
    public function test_array_list(): void
    {
        $rule = new ArrayList();

        static::assertFalse($rule->isValid(['foo' => 'bar']));
        static::assertTrue($rule->isValid([]));
        static::assertTrue($rule->isValid(['a', 'b', 'c']));
        static::assertFalse($rule->isValid([0 => 'a', 1 => 'b', 3 => 'c']));
        static::assertSame('Value must be a list', $rule->message());
    }
}
