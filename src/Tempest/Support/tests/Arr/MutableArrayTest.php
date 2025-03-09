<?php

declare(strict_types=1);

namespace Tempest\Support\Tests\Arr;

use PHPUnit\Framework\TestCase;
use Tempest\Support\Arr\MutableArray;

/**
 * @internal
 */
final class MutableArrayTest extends TestCase
{
    public function test_add(): void
    {
        $collection = new MutableArray('a');

        static::assertSame(
            $collection->add('b')->toArray(),
            ['a', 'b'],
        );

        static::assertSame(
            $collection->add('b')->add('c')->toArray(),
            ['a', 'b', 'b', 'c'],
        );
    }

    public function test_add_diverse_values(): void
    {
        $collection = new MutableArray();

        static::assertSame(
            $collection->add(1)->toArray(),
            [1],
        );

        static::assertSame(
            $collection->add(2)->toArray(),
            [1, 2],
        );

        static::assertSame(
            $collection->add('')->toArray(),
            [1, 2, ''],
        );

        static::assertSame(
            $collection->add(null)->toArray(),
            [1, 2, '', null],
        );

        static::assertSame(
            $collection->add(false)->toArray(),
            [1, 2, '', null, false],
        );

        static::assertSame(
            $collection->add([])->toArray(),
            [1, 2, '', null, false, []],
        );

        static::assertSame(
            actual: $collection->add('name')->toArray(),
            expected: [1, 2, '', null, false, [], 'name'],
        );
    }

    public function test_remove_with_basic_keys(): void
    {
        $collection = new MutableArray([1, 2, 3]);

        static::assertEquals(
            $collection->remove(1)->toArray(),
            [0 => 1, 2 => 3],
        );

        static::assertEquals(
            $collection->remove([0, 2])->toArray(),
            [],
        );
    }

    public function test_remove_with_associative_keys(): void
    {
        $collection = new MutableArray([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 42,
        ]);

        static::assertEquals(
            $collection->remove('first_name')->toArray(),
            ['last_name' => 'Doe', 'age' => 42],
        );

        static::assertEquals(
            $collection->remove(['last_name', 'age'])->toArray(),
            [],
        );
    }

    public function test_pull(): void
    {
        $collection = new MutableArray([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 42,
        ]);

        static::assertSame(
            $collection->pull('first_name'),
            'John',
        );

        static::assertSame(
            $collection->toArray(),
            ['last_name' => 'Doe', 'age' => 42],
        );
    }
}
