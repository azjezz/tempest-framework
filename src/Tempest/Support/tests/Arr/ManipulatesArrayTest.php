<?php

declare(strict_types=1);

namespace Tempest\Support\Tests\Arr;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Tempest\Support\Arr\ImmutableArray;
use Tempest\Support\Arr\InvalidMapWithKeysUsage;

use function Tempest\Support\arr;
use function Tempest\Support\str;

/**
 * @internal
 */
final class ManipulatesArrayTest extends TestCase
{
    public function test_wrap(): void
    {
        static::assertTrue(arr()->equals([]));
        static::assertTrue(arr('a')->equals(['a']));
        static::assertTrue(arr(arr('a'))->equals(['a']));
        static::assertTrue(arr(['a'])->equals(['a']));
    }

    public function test_to_array(): void
    {
        static::assertSame(['a'], arr('a')->toArray());
    }

    public function test_loop(): void
    {
        $i = 0;

        foreach (arr(['a', 'b']) as $value) {
            $i++;
        }

        static::assertSame(2, $i);
    }

    public function test_count(): void
    {
        static::assertSame(2, arr(['a', 'b'])->count());
    }

    public function test_serialize(): void
    {
        $array = ['a', 'b'];

        static::assertTrue(arr($array)->equals(unserialize(serialize($array))));
    }

    public function test_array_access(): void
    {
        $array = arr(['a' => 1, 'b' => 2]);

        static::assertSame(1, $array['a']);
        static::assertSame(2, $array['b']);
        static::assertTrue(isset($array['a']));
        static::assertFalse(isset($array['x']));

        unset($array['a']);
        static::assertFalse(isset($array['a']));
    }

    public function test_get_dot(): void
    {
        $array = [
            'a' => [
                'b' => 'c',
            ],
        ];

        static::assertSame('c', arr($array)->get('a.b'));
        static::assertInstanceOf(ImmutableArray::class, arr($array)->get('a'));
        static::assertNull(arr($array)->get('a.x'));
        static::assertSame('default', arr($array)->get('a.x', 'default'));
    }

    public function test_get(): void
    {
        $array = [
            'b.c' => 'd',
            'a' => 'b',
        ];

        static::assertSame('d', arr($array)->get('b.c'));
        static::assertSame('b', arr($array)->get('a'));
    }

    public function test_arr_has(): void
    {
        $array = [
            'a' => [
                'b' => 'c',
            ],
        ];

        static::assertTrue(arr($array)->has('a.b'));
        static::assertTrue(arr($array)->has('a'));
        static::assertFalse(arr($array)->has('a.x'));
    }

    public function test_arr_set(): void
    {
        $array = [
            'a' => [
                'b' => [
                    'c' => 'c',
                ],
            ],
        ];

        static::assertTrue(arr()->set('a.b.c', 'c')->equals($array));
        static::assertTrue(arr($array)->set('a', 'c')->equals(['a' => 'c']));
    }

    public function test_arr_put_is_alias_of_set(): void
    {
        $array = [
            'a' => [
                'b' => [
                    'c' => 'c',
                ],
            ],
        ];

        static::assertTrue(arr()->set('a.b.c', 'c')->equals($array));
        static::assertTrue(arr()->put('a.b.c', 'c')->equals($array));
        static::assertTrue(arr($array)->set('a', 'c')->equals(['a' => 'c']));
        static::assertTrue(arr($array)->put('a', 'c')->equals(['a' => 'c']));
    }

    public function test_unwrap(): void
    {
        $expected = [
            'a' => [
                'b' => [
                    'c' => 'c',
                ],
            ],
        ];

        $input = [
            'a.b.c' => 'c',
        ];

        static::assertTrue(arr($input)->unwrap()->equals($expected));
    }

    public function test_implode(): void
    {
        static::assertEquals(str('a,b,c'), arr(['a', 'b', 'c'])->implode(','));
    }

    #[TestWith([['Jon', 'Jane'], 'Jon and Jane'])]
    #[TestWith([['Jon', 'Jane', 'Jill'], 'Jon, Jane and Jill'])]
    public function test_join(array $initial, string $expected): void
    {
        static::assertEquals($expected, arr($initial)->join());
    }

    #[TestWith([['Jon', 'Jane'], ', ', ' and maybe ', 'Jon and maybe Jane'])]
    #[TestWith([['Jon', 'Jane', 'Jill'], ' + ', ' and ', 'Jon + Jane and Jill'])]
    #[TestWith([['Jon', 'Jane', 'Jill'], ' + ', null, 'Jon + Jane + Jill'])]
    public function test_join_with_glues(array $initial, string $glue, ?string $finalGlue, string $expected): void
    {
        static::assertTrue(arr($initial)->join($glue, $finalGlue)->equals($expected));
    }

    public function test_pop(): void
    {
        $array = arr(['a', 'b', 'c'])->pop($value);

        static::assertSame('c', $value);
        static::assertTrue($array->equals(['a', 'b']));

        static::assertTrue(arr(['a', 'b', 'c'])->pop()->equals(['a', 'b']));
        static::assertTrue(arr()->pop()->isEmpty());

        arr()->pop($value);
        static::assertNull($value);
    }

    public function test_unshift(): void
    {
        $array = arr(['a', 'b', 'c'])->unshift($value);

        static::assertSame('a', $value);
        static::assertTrue($array->equals(['b', 'c']));

        static::assertTrue(arr(['a', 'b', 'c'])->unshift()->equals(['b', 'c']));
        static::assertTrue(arr()->unshift()->isEmpty());

        arr()->unshift($value);
        static::assertNull($value);
    }

    public function test_last(): void
    {
        static::assertSame(null, arr()->last());
        static::assertSame('c', arr(['a', 'b', 'c'])->last());
    }

    public function test_first(): void
    {
        static::assertSame('a', arr(['a', 'b', 'c'])->first());
        static::assertSame(null, arr()->first());
    }

    public function test_is_empty(): void
    {
        static::assertTrue(arr()->isEmpty());
        static::assertFalse(arr(['a'])->isEmpty());
    }

    public function test_map(): void
    {
        static::assertTrue(
            arr(['a', 'b'])
                ->map(fn (string $value) => $value . 'x')
                ->equals(['ax', 'bx']),
        );

        static::assertTrue(
            arr(['a', 'b'])
                ->map(fn (string $value, mixed $key) => $value . $key)
                ->equals(['a0', 'b1']),
        );
    }

    public function test_map_with_keys(): void
    {
        static::assertTrue(
            arr(['a', 'b'])
                ->mapWithKeys(fn (mixed $value, mixed $key) => yield $value => $value)
                ->equals(['a' => 'a', 'b' => 'b']),
        );

        static::assertTrue(
            arr(['a' => 'a', 'b' => 'b'])
                ->mapWithKeys(fn (mixed $value, mixed $key) => yield $value)
                ->equals(['b']),
        );
    }

    public function test_map_with_keys_without_generator(): void
    {
        $this->expectException(InvalidMapWithKeysUsage::class);

        arr(['a', 'b'])->mapWithKeys(fn (mixed $value, mixed $key) => $value);
    }

    public function test_values(): void
    {
        static::assertTrue(
            arr(['a' => 'a', 'b' => 'b'])
                ->values()
                ->equals(['a', 'b']),
        );
    }

    public function test_filter(): void
    {
        static::assertSame(
            ['a', 'b', '-1', -1, '0', 0],
            arr(['a', false, 'b', '-1', null, -1, '0', 0])
                ->filter()
                ->values()
                ->toArray(),
        );

        static::assertTrue(
            arr(['a', 'b', 'c'])
                ->filter(fn (mixed $value) => $value === 'b')
                ->values()
                ->equals(['b']),
        );

        static::assertTrue(
            arr(['a', 'b', 'c'])
                ->filter(fn (mixed $value, mixed $key) => $key === 1)
                ->values()
                ->equals(['b']),
        );
    }

    public function test_reverse(): void
    {
        static::assertTrue(
            arr(['a', 'b', 'c'])
                ->reverse()
                ->equals(['c', 'b', 'a']),
        );
    }

    public function test_each(): void
    {
        $string = '';

        arr(['a', 'b', 'c'])
            ->each(function (mixed $value) use (&$string): void {
                $string .= $value;
            });

        static::assertSame('abc', $string);

        $string = '';

        arr(['a', 'b', 'c'])
            ->each(function (mixed $value, mixed $key) use (&$string): void {
                $string .= $key;
            });

        static::assertSame('012', $string);
    }

    public function test_contains(): void
    {
        static::assertTrue(arr(['a', 'b', 'c'])->contains('b'));
        static::assertFalse(arr(['a', 'b', 'c'])->contains('d'));
    }

    public function test_explode(): void
    {
        static::assertEquals(['john', 'doe'], ImmutableArray::explode('john doe')->toArray());
        static::assertEquals(['john', 'doe'], ImmutableArray::explode(str('john doe'))->toArray());
        static::assertEquals(['john doe'], ImmutableArray::explode('john doe', ',')->toArray());
        static::assertEquals(['john', 'doe'], ImmutableArray::explode('john, doe', ', ')->toArray());
        static::assertEquals(['john, doe'], ImmutableArray::explode('john, doe', '')->toArray());
    }

    public function test_combine_with_integers(): void
    {
        $collection = arr([1, 2, 3]);
        $current = $collection
            ->combine([4, 5, 6])
            ->toArray();
        $expected = [
            1 => 4,
            2 => 5,
            3 => 6,
        ];

        static::assertSame($expected, $current);
    }

    public function test_combine_with_strings(): void
    {
        $collection = arr([
            'first_name',
            'last_name',
        ]);
        $current = $collection
            ->combine([
                'John',
                'Doe',
            ])
            ->toArray();
        $expected = [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ];

        static::assertSame($expected, $current);
    }

    public function test_combine_with_associative_arrays(): void
    {
        $collection = arr([
            5 => 'first_name',
            'test' => 'last_name',
            42 => 'age',
        ]);
        $current = $collection
            ->combine([
                4 => 'John',
                5 => 'Doe',
                6 => 50,
            ])
            ->toArray();
        $expected = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 50,
        ];

        static::assertSame($expected, $current);
    }

    public function test_combine_with_collection(): void
    {
        $collection = arr(['first_name', 'last_name']);
        $other = arr(['John', 'Doe']);
        $combined = $collection->combine($other)->toArray();

        $expected = [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ];

        static::assertSame($expected, $combined);
    }

    public function test_keys(): void
    {
        $collection = arr([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'framework' => 'Tempest',
        ]);
        $current = $collection
            ->keys()
            ->toArray();
        $expected = [
            'first_name',
            'last_name',
            'framework',
        ];

        static::assertSame($expected, $current);
    }

    public function test_merge_array(): void
    {
        $collection = arr([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $current = $collection
            ->merge([
                'framework' => 'Tempest',
            ])
            ->toArray();

        $expected = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'framework' => 'Tempest',
        ];

        static::assertSame($expected, $current);
    }

    public function test_merge_collection(): void
    {
        $collection = arr([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $current = $collection
            ->merge(arr([
                'framework' => 'Tempest',
            ]))
            ->toArray();

        $expected = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'framework' => 'Tempest',
        ];

        static::assertSame($expected, $current);
    }

    public function test_diff_values(): void
    {
        $collection = arr([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 42,
        ]);
        $current = $collection
            ->diff([
                'John',
                'Doe',
            ])
            ->toArray();
        $expected = [
            'age' => 42,
        ];

        static::assertSame($expected, $current);
    }

    public function test_diff_keys(): void
    {
        $collection = arr([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 42,
        ]);
        $current = $collection
            ->diffKeys([
                'age' => 10,
            ])
            ->toArray();
        $expected = [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ];

        static::assertSame($expected, $current);
    }

    public function test_intersect(): void
    {
        $collection = arr([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 42,
        ]);
        $current = $collection
            ->intersect([
                'John',
                'Doe',
            ])
            ->toArray();
        $expected = [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ];

        static::assertSame($expected, $current);
    }

    public function test_intersect_keys(): void
    {
        $collection = arr([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 42,
        ]);
        $current = $collection
            ->intersectKeys([
                'first_name' => true,
                'last_name' => true,
            ])
            ->toArray();
        $expected = [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ];

        static::assertSame($expected, $current);
    }

    public function test_unique_with_basic_item(): void
    {
        $collection = arr([
            'John',
            'Doe',
            'John',
            'Doe',
            'Jane',
            'Doe',
        ]);
        $current = $collection
            ->unique()
            ->values()
            ->toArray();
        $expected = [
            'John',
            'Doe',
            'Jane',
        ];

        static::assertSame($expected, $current);
    }

    public function test_unique_callback(): void
    {
        $collection = arr([
            'John',
            'Doe',
            'John',
            'Doe',
            'Jane',
            'Doe',
        ]);

        $current = $collection
            ->unique(fn (string $item) => $item[0])
            ->values()
            ->toArray();

        $expected = [
            'John',
            'Doe',
        ];

        static::assertSame($expected, $current);
    }

    public function test_unique_with_associative_array(): void
    {
        $collection = arr([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 42,
            'steal_name' => 'John',
        ]);
        $current = $collection
            ->unique()
            ->toArray();
        $expected = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 42,
        ];

        static::assertSame($expected, $current);
    }

    public function test_unique_with_arrays(): void
    {
        $collection = arr([
            ['John', 'Doe'],
            ['John', 'Doe'],
            [1, 2],
            [1, 2],
            [3, 4],
        ]);
        $current = $collection
            ->unique()
            ->values()
            ->toArray();
        $expected = [
            ['John', 'Doe'],
            [1, 2],
            [3, 4],
        ];

        static::assertSame($expected, $current);
    }

    public function test_unique_with_key(): void
    {
        $collection = arr([
            ['id' => 1, 'first_name' => 'John', 'last_name' => 'Doe'],
            ['id' => 2, 'first_name' => 'John', 'last_name' => 'Doe'],
            ['id' => 3, 'first_name' => 'Jane', 'last_name' => 'Doe'],
            ['id' => 3, 'first_name' => 'Jane', 'last_name' => 'Duplicate'],
        ]);

        static::assertSame(
            $collection
                ->unique('first_name')
                ->values()
                ->toArray(),
            [
                ['id' => 1, 'first_name' => 'John', 'last_name' => 'Doe'],
                ['id' => 3, 'first_name' => 'Jane', 'last_name' => 'Doe'],
            ],
        );

        static::assertSame(
            $collection
                ->unique('last_name')
                ->values()
                ->toArray(),
            [
                ['id' => 1, 'first_name' => 'John', 'last_name' => 'Doe'],
                ['id' => 3, 'first_name' => 'Jane', 'last_name' => 'Duplicate'],
            ],
        );

        static::assertSame(
            $collection
                ->unique('id')
                ->values()
                ->toArray(),
            [
                ['id' => 1, 'first_name' => 'John', 'last_name' => 'Doe'],
                ['id' => 2, 'first_name' => 'John', 'last_name' => 'Doe'],
                ['id' => 3, 'first_name' => 'Jane', 'last_name' => 'Doe'],
            ],
        );
    }

    public function test_unique_ensure_unnested_value_is_rejected_when_key_is_set(): void
    {
        $collection = arr([
            42,
            'Hello World',
            ['id' => 1, 'first_name' => 'John', 'last_name' => 'Doe'],
            ['id' => 2, 'first_name' => 'John', 'last_name' => 'Doe'],
            ['id' => 3, 'first_name' => 'Jane', 'last_name' => 'Doe'],
            ['id' => 3, 'first_name' => 'Jane', 'last_name' => 'Duplicate'],
        ]);

        static::assertSame(
            $collection
                ->unique('id')
                ->values()
                ->toArray(),
            [
                ['id' => 1, 'first_name' => 'John', 'last_name' => 'Doe'],
                ['id' => 2, 'first_name' => 'John', 'last_name' => 'Doe'],
                ['id' => 3, 'first_name' => 'Jane', 'last_name' => 'Doe'],
            ],
        );
    }

    public function test_unique_unstrict_check(): void
    {
        static::assertSame(
            arr([
                42,
                '42',
                true,
                'true',
            ])
                ->unique(shouldBeStrict: false)
                ->values()
                ->toArray(),
            [
                42,
                'true',
            ],
        );
    }

    public function test_unique_strict_check(): void
    {
        static::assertSame(
            arr([
                42,
                '42',
                true,
                'true',
            ])
                ->unique(shouldBeStrict: true)
                ->values()
                ->toArray(),
            [
                42,
                '42',
                true,
                'true',
            ],
        );
    }

    public function test_unique_with_key_and_strict_check(): void
    {
        static::assertSame(
            arr([
                ['id' => 1, 'first_name' => 'John', 'last_name' => 'Doe'],
                ['id' => '1', 'first_name' => 'John', 'last_name' => 'Doe'],
                ['id' => 2, 'first_name' => 'John', 'last_name' => 'Doe'],
                ['id' => 3, 'first_name' => 'Jane', 'last_name' => 'Doe'],
                ['id' => 3, 'first_name' => 'Jane', 'last_name' => 'Duplicate'],
            ])
                ->unique('id', shouldBeStrict: true)
                ->values()
                ->toArray(),
            [
                ['id' => 1, 'first_name' => 'John', 'last_name' => 'Doe'],
                ['id' => '1', 'first_name' => 'John', 'last_name' => 'Doe'],
                ['id' => 2, 'first_name' => 'John', 'last_name' => 'Doe'],
                ['id' => 3, 'first_name' => 'Jane', 'last_name' => 'Doe'],
            ],
        );
    }

    public function test_unique_key_dot_notation(): void
    {
        $collection = arr([
            [
                'id' => 1,
                'title' => 'First Post',
                'author' => ['id' => 1, 'name' => 'John Doe'],
            ],
            [
                'id' => 2,
                'title' => 'Second Post',
                'author' => ['id' => 2, 'name' => 'Jane Smith'],
            ],
            [
                'id' => 3,
                'title' => 'Third Post',
                'author' => ['id' => 1, 'name' => 'John Doe'], // Duplicate author
            ],
            [
                'id' => 4,
                'title' => 'Fourth Post',
                'author' => ['id' => 3, 'name' => 'Alice Johnson'],
            ],
            [
                'id' => 5,
                'title' => 'Fifth Post',
                'author' => ['id' => 2, 'name' => 'Jane Smith'], // Duplicate author
            ],
            [
                'id' => 6,
                'title' => 'Sixth Post',
                'author' => ['id' => 4, 'name' => 'Bob Brown'],
            ],
        ]);

        static::assertSame(
            $collection
                ->unique('author.id')
                ->values()
                ->toArray(),
            [
                [
                    'id' => 1,
                    'title' => 'First Post',
                    'author' => ['id' => 1, 'name' => 'John Doe'],
                ],
                [
                    'id' => 2,
                    'title' => 'Second Post',
                    'author' => ['id' => 2, 'name' => 'Jane Smith'],
                ],
                [
                    'id' => 4,
                    'title' => 'Fourth Post',
                    'author' => ['id' => 3, 'name' => 'Alice Johnson'],
                ],
                [
                    'id' => 6,
                    'title' => 'Sixth Post',
                    'author' => ['id' => 4, 'name' => 'Bob Brown'],
                ],
            ],
        );
    }

    public function test_flip(): void
    {
        static::assertSame(
            arr([
                'first_name' => 'John',
                'last_name' => 'Doe',
            ])
                ->flip()
                ->toArray(),
            [
                'John' => 'first_name',
                'Doe' => 'last_name',
            ],
        );
    }

    public function test_pad(): void
    {
        static::assertSame(
            arr([1, 2, 3])
                ->pad(4, 0)
                ->toArray(),
            [1, 2, 3, 0],
        );

        static::assertSame(
            arr([1, 2, 3, 4, 5])
                ->pad(4, 0)
                ->toArray(),
            [1, 2, 3, 4, 5],
        );

        static::assertSame(
            arr([1, 2, 3])
                ->pad(-4, 0)
                ->toArray(),
            [0, 1, 2, 3],
        );

        static::assertSame(
            arr([1, 2, 3, 4, 5])
                ->pad(-4, 0)
                ->toArray(),
            [1, 2, 3, 4, 5],
        );
    }

    public function test_add(): void
    {
        $collection = new ImmutableArray('a');

        static::assertSame(
            $collection->add('b')->toArray(),
            ['a', 'b'],
        );
    }

    public function test_add_diverse_values(): void
    {
        $collection = new ImmutableArray();

        static::assertSame(
            $collection->add(1)->toArray(),
            [1],
        );

        static::assertSame(
            $collection->add(2)->toArray(),
            [2],
        );

        static::assertSame(
            $collection->add('')->toArray(),
            [''],
        );

        static::assertSame(
            $collection->add(null)->toArray(),
            [null],
        );

        static::assertSame(
            $collection->add(false)->toArray(),
            [false],
        );

        static::assertSame(
            $collection->add([])->toArray(),
            [[]],
        );

        static::assertSame(
            actual: $collection->add('name')->toArray(),
            expected: ['name'],
        );
    }

    public function test_push_is_alias_of_add(): void
    {
        $first_collection = arr()
            ->add(42)
            ->add('Hello')
            ->add([])
            ->add(false)
            ->add(null);
        $second_collection = arr()
            ->push(42)
            ->push('Hello')
            ->push([])
            ->push(false)
            ->push(null);

        static::assertTrue($first_collection->equals($second_collection));
    }

    public function test_pluck_without_arrays(): void
    {
        static::assertSame(
            arr([
                'name' => 'John',
                'age' => 42,
            ])
                ->pluck('name')
                ->toArray(),
            [],
        );
    }

    public function test_pluck_basics(): void
    {
        $collection = arr([
            ['name' => 'John', 'age' => 42],
            ['name' => 'Jane', 'age' => 35],
            ['name' => 'Alice', 'age' => 28],
        ]);

        static::assertSame(
            $collection
                ->pluck('name')
                ->toArray(),
            ['John', 'Jane', 'Alice'],
        );

        static::assertSame(
            $collection
                ->pluck('age')
                ->toArray(),
            [42, 35, 28],
        );

        static::assertSame(
            $collection
                ->pluck('name', 'age')
                ->toArray(),
            [
                42 => 'John',
                35 => 'Jane',
                28 => 'Alice',
            ],
        );

        static::assertSame(
            $collection
                ->pluck('age', 'name')
                ->toArray(),
            [
                'John' => 42,
                'Jane' => 35,
                'Alice' => 28,
            ],
        );
    }

    public function test_pluck_dot_notation(): void
    {
        $collection = arr([
            [
                'id' => 1,
                'title' => 'First Post',
                'author' => ['id' => 1, 'name' => 'John Doe'],
            ],
            [
                'id' => 2,
                'title' => 'Second Post',
                'author' => ['id' => 2, 'name' => 'Jane Smith'],
            ],
            [
                'id' => 3,
                'title' => 'Third Post',
                'author' => ['id' => 1, 'name' => 'John Doe'],
            ],
            [
                'id' => 4,
                'title' => 'Fourth Post',
                'author' => ['id' => 3, 'name' => 'Alice Johnson'],
            ],
            [
                'id' => 5,
                'title' => 'Fifth Post',
                'author' => ['id' => 2, 'name' => 'Jane Smith'],
            ],
            [
                'id' => 6,
                'title' => 'Sixth Post',
                'author' => ['id' => 4, 'name' => 'Bob Brown'],
            ],
        ]);

        static::assertSame(
            $collection
                ->pluck('author.name')
                ->toArray(),
            [
                'John Doe',
                'Jane Smith',
                'John Doe',
                'Alice Johnson',
                'Jane Smith',
                'Bob Brown',
            ],
        );

        static::assertSame(
            $collection
                ->pluck('author.name', 'id')
                ->toArray(),
            [
                1 => 'John Doe',
                2 => 'Jane Smith',
                3 => 'John Doe',
                4 => 'Alice Johnson',
                5 => 'Jane Smith',
                6 => 'Bob Brown',
            ],
        );

        static::assertSame(
            $collection
                ->pluck('author.name', 'author.id')
                ->toArray(),
            [
                1 => 'John Doe',
                2 => 'Jane Smith',
                3 => 'Alice Johnson',
                4 => 'Bob Brown',
            ],
        );
    }

    public function test_random(): void
    {
        $collection = arr([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        $random = $collection->random();
        static::assertIsInt($random);
        static::assertContains($random, $collection->toArray());

        $randoms = $collection->random(3);
        foreach ($randoms as $value) {
            static::assertIsInt($value);
            static::assertContains($value, $collection->toArray());
        }

        static::assertCount(3, $randoms);
    }

    public function test_random_with_preserve_keys(): void
    {
        $collection = arr([
            'id' => 1,
            'title' => 'First Post',
            'author' => ['id' => 1, 'name' => 'John Doe'],
        ]);

        $randoms = $collection->random(3, preserveKey: true);

        static::assertCount(3, $randoms);
        static::assertArrayHasKey('id', $randoms);
        static::assertArrayHasKey('title', $randoms);
        static::assertArrayHasKey('author', $randoms);

        $randoms = $collection->random(2, preserveKey: true);

        static::assertCount(2, array_intersect_key($collection->toArray(), $randoms->toArray()));
    }

    public function test_random_on_empty_array(): void
    {
        $collection = arr();

        $this->expectException(InvalidArgumentException::class);

        $collection->random();
    }

    public function test_random_with_count_superior_than_array_count(): void
    {
        $collection = arr([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        $this->expectException(InvalidArgumentException::class);

        $collection->random(15);
    }

    public function test_random_throw_exception_when_giving_negative_integer(): void
    {
        $collection = arr([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        $this->expectException(InvalidArgumentException::class);

        $collection->random(-1);
    }

    public function test_is_list(): void
    {
        static::assertTrue(arr()->isList());
        static::assertTrue(arr(['a', 2, 3])->isList());
        static::assertTrue(arr([0 => 'a', 'b'])->isList());

        static::assertFalse(arr([1 => 'a', 'b'])->isList());
        static::assertFalse(arr([1 => 'a', 0 => 'b'])->isList());
        static::assertFalse(arr([0 => 'a', 'foo' => 'b'])->isList());
        static::assertFalse(arr([0 => 'a', 2 => 'b'])->isList());
    }

    public function test_is_assoc(): void
    {
        static::assertTrue(arr([1 => 'a', 'b'])->isAssociative());
        static::assertTrue(arr([1 => 'a', 0 => 'b'])->isAssociative());
        static::assertTrue(arr([0 => 'a', 'foo' => 'b'])->isAssociative());
        static::assertTrue(arr([0 => 'a', 2 => 'b'])->isAssociative());

        static::assertFalse(arr()->isAssociative());
        static::assertFalse(arr([1, 2, 3])->isAssociative());
        static::assertFalse(arr(['a', 2, 3])->isAssociative());
        static::assertFalse(arr([0 => 'a', 'b'])->isAssociative());

        static::assertTrue(arr([0 => 'a', 'foo' => 'b'])->isAssociative());
        static::assertTrue(arr([0 => 'a', 2 => 'b'])->isAssociative());
        static::assertTrue(arr(['foo' => 'a', 'baz' => 'b'])->isAssociative());
    }

    public function test_remove_with_basic_keys(): void
    {
        $collection = arr([1, 2, 3]);

        static::assertEquals(
            $collection->remove(1)->toArray(),
            [0 => 1, 2 => 3],
        );

        static::assertEquals(
            $collection->remove([0, 2])->toArray(),
            [1 => 2],
        );
    }

    public function test_remove_with_associative_keys(): void
    {
        $collection = new ImmutableArray([
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
            ['first_name' => 'John'],
        );
    }

    public function test_remove_with_no_valid_key(): void
    {
        $collection = arr([1, 2, 3]);

        static::assertEquals(
            $collection
                ->remove(42)
                ->toArray(),
            [1, 2, 3],
        );

        $collection = arr([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 42,
        ]);

        static::assertEquals(
            $collection
                ->remove('foo')
                ->toArray(),
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'age' => 42,
            ],
        );

        static::assertEquals(
            $collection
                ->remove(['bar', 'first_name'])
                ->toArray(),
            [
                'last_name' => 'Doe',
                'age' => 42,
            ],
        );
    }

    public function test_forget_is_alias_of_remove(): void
    {
        $first_collection = arr([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 42,
        ])
            ->remove(42)
            ->remove('foo')
            ->remove(['bar', 'first_name']);

        $second_collection = arr([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 42,
        ])
            ->forget(42)
            ->forget('foo')
            ->forget(['bar', 'first_name']);

        static::assertTrue($first_collection->equals($second_collection));
    }

    public function test_shuffle_actually_shuffles(): void
    {
        $array = range('a', 'z');

        static::assertNotEquals(arr($array)->shuffle()->toArray(), $array);
        static::assertNotEquals(arr($array)->shuffle()->toArray(), $array);
    }

    public function test_shuffle_keeps_same_values(): void
    {
        $array = range('a', 'z');
        $shuffled = arr($array)->shuffle()->toArray();
        sort($shuffled);

        static::assertSame($shuffled, $array);
    }

    public function test_sort(): void
    {
        $array = arr([1 => 'c', 2 => 'a', 3 => 'b']);

        // Test auto-detects key preservation
        static::assertSame(
            ['a', 'b', 'c'],
            arr(['c', 'a', 'b'])->sort()->toArray(),
        );
        static::assertSame(
            [2 => 'a', 3 => 'b', 1 => 'c'],
            $array->sort()->toArray(),
        );

        static::assertSame(
            ['a', 'b', 'c'],
            $array->sort(desc: false, preserveKeys: false)->toArray(),
        );
        static::assertSame(
            ['c', 'b', 'a'],
            $array->sort(desc: true, preserveKeys: false)->toArray(),
        );

        static::assertSame(
            [2 => 'a', 3 => 'b', 1 => 'c'],
            $array->sort(desc: false, preserveKeys: true)->toArray(),
        );
        static::assertSame(
            [1 => 'c', 3 => 'b', 2 => 'a'],
            $array->sort(desc: true, preserveKeys: true)->toArray(),
        );
    }

    public function test_sort_by_callback(): void
    {
        $array = arr([1 => 'c', 2 => 'a', 3 => 'b']);

        // Test auto-detects key preservation
        static::assertSame(
            ['a', 'b', 'c'],
            arr(['c', 'a', 'b'])->sortByCallback(fn ($a, $b) => $a <=> $b)->toArray(),
        );
        static::assertSame(
            [2 => 'a', 3 => 'b', 1 => 'c'],
            $array->sortByCallback(fn ($a, $b) => $a <=> $b)->toArray(),
        );

        static::assertSame(
            ['a', 'b', 'c'],
            $array->sortByCallback(
                    callback: fn ($a, $b) => $a <=> $b,
                    preserveKeys: false,
                )->toArray(),
        );
        static::assertSame(
            [2 => 'a', 3 => 'b', 1 => 'c'],
            $array->sortByCallback(
                    callback: fn ($a, $b) => $a <=> $b,
                    preserveKeys: true,
                )->toArray(),
        );
    }

    public function test_sort_keys(): void
    {
        $array = arr([2 => 'a', 1 => 'c', 3 => 'b']);

        static::assertSame(
            [1 => 'c', 2 => 'a', 3 => 'b'],
            $array->sortKeys(desc: false)->toArray(),
        );
        static::assertSame(
            [3 => 'b', 2 => 'a', 1 => 'c'],
            $array->sortKeys(desc: true)->toArray(),
        );
    }

    public function test_sort_keys_by_callback(): void
    {
        $array = arr([2 => 'a', 1 => 'c', 3 => 'b']);

        static::assertSame(
            [1 => 'c', 2 => 'a', 3 => 'b'],
            $array->sortKeysByCallback(fn ($a, $b) => $a <=> $b)->toArray(),
        );
    }

    public function test_flatten(): void
    {
        static::assertTrue(arr(['#foo', '#bar', '#baz'])->flatten()->equals(['#foo', '#bar', '#baz']));
        static::assertTrue(arr([['#foo', '#bar'], '#baz'])->flatten()->equals(['#foo', '#bar', '#baz']));
        static::assertTrue(arr([['#foo', null], '#baz', null])->flatten()->equals(['#foo', null, '#baz', null]));
        static::assertTrue(arr([['#foo', '#bar'], ['#baz']])->flatten()->equals(['#foo', '#bar', '#baz']));
        static::assertTrue(arr([['#foo', ['#bar']], ['#baz']])->flatten()->equals(['#foo', '#bar', '#baz']));
        static::assertTrue(arr([['#foo', ['#bar', ['#baz']]], '#zap'])->flatten()->equals(['#foo', '#bar', '#baz', '#zap']));

        static::assertTrue(arr([['#foo', ['#bar', ['#baz']]], '#zap'])->flatten(depth: 1)->equals(['#foo', ['#bar', ['#baz']], '#zap']));
        static::assertTrue(arr([['#foo', ['#bar', ['#baz']]], '#zap'])->flatten(depth: 2)->equals(['#foo', '#bar', ['#baz'], '#zap']));
    }

    public function test_flatmap(): void
    {
        // basic
        static::assertTrue(
            arr([
                ['name' => 'Makise', 'hobbies' => ['Science', 'Programming']],
                ['name' => 'Okabe', 'hobbies' => ['Science', 'Anime']],
            ])
                ->flatMap(fn (array $person) => $person['hobbies'])
                ->equals(['Science', 'Programming', 'Science', 'Anime']),
        );

        // deeply nested
        $likes = arr([
            [
                'name' => 'Enzo',
                'likes' => [
                    'manga' => ['Tower of God', 'The Beginning After The End'],
                    'languages' => ['PHP', 'TypeScript'],
                ],
            ],
            [
                'name' => 'Jon',
                'likes' => [
                    'manga' => ['One Piece', 'Naruto'],
                    'languages' => ['Python'],
                ],
            ],
        ]);

        static::assertTrue(
            $likes
                ->flatMap(fn (array $person) => $person['likes'], depth: 1)
                ->equals([
                    ['Tower of God', 'The Beginning After The End'],
                    ['PHP', 'TypeScript'],
                    ['One Piece', 'Naruto'],
                    ['Python'],
                ]),
        );

        static::assertTrue(
            $likes
                ->flatMap(fn (array $person) => $person['likes'], depth: INF)
                ->equals([
                    'Tower of God',
                    'The Beginning After The End',
                    'PHP',
                    'TypeScript',
                    'One Piece',
                    'Naruto',
                    'Python',
                ]),
        );
    }

    public function test_basic_reduce(): void
    {
        $collection = arr([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 42,
        ]);

        static::assertSame(
            $collection->reduce(fn ($carry, $value) => $carry . ' ' . $value, 'Hello'),
            'Hello John Doe 42',
        );
    }

    public function test_reduce_with_existing_function(): void
    {
        $collection = arr([
            [1, 2, 2, 3],
            [2, 3, 3, 4],
            [3, 1, 3, 1],
        ]);

        static::assertSame(
            $collection->reduce('max'),
            [3, 1, 3, 1],
        );
    }

    public function test_empty_array_reduce(): void
    {
        static::assertSame(
            arr()->reduce(fn ($carry, $value) => $carry . ' ' . $value, 'default'),
            'default',
        );
    }

    public function test_chunk(): void
    {
        $collection = arr([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        static::assertSame(
            $collection
                ->chunk(2, preserveKeys: false)
                ->map(fn ($chunk) => $chunk->toArray())
                ->toArray(),
            [
                [1, 2],
                [3, 4],
                [5, 6],
                [7, 8],
                [9, 10],
            ],
        );

        static::assertSame(
            $collection
                ->chunk(3, preserveKeys: false)
                ->map(fn ($chunk) => $chunk->toArray())
                ->toArray(),
            [
                [1, 2, 3],
                [4, 5, 6],
                [7, 8, 9],
                [10],
            ],
        );
    }

    public function test_chunk_preserve_keys(): void
    {
        $collection = arr([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        static::assertSame(
            $collection
                ->chunk(2)
                ->map(fn ($chunk) => $chunk->toArray())
                ->toArray(),
            [
                [0 => 1, 1 => 2],
                [2 => 3, 3 => 4],
                [4 => 5, 5 => 6],
                [6 => 7, 7 => 8],
                [8 => 9, 9 => 10],
            ],
        );

        static::assertSame(
            $collection
                ->chunk(3)
                ->map(fn ($chunk) => $chunk->toArray())
                ->toArray(),
            [
                [0 => 1, 1 => 2, 2 => 3],
                [3 => 4, 4 => 5, 5 => 6],
                [6 => 7, 7 => 8, 8 => 9],
                [9 => 10],
            ],
        );
    }

    public function test_find_key_with_simple_value(): void
    {
        $collection = arr(['apple', 'banana', 'orange']);

        static::assertSame(1, $collection->findKey('banana'));
        static::assertSame(0, $collection->findKey('apple'));
        static::assertNull($collection->findKey('grape'));
    }

    public function test_find_key_with_strict_comparison(): void
    {
        $collection = arr([1, '1', 2, '2']);

        static::assertSame(0, $collection->findKey(1, strict: false));
        static::assertSame(0, $collection->findKey('1', strict: false));

        static::assertSame(0, $collection->findKey(1, strict: true));
        static::assertSame(1, $collection->findKey('1', strict: true));
    }

    public function test_find_key_with_closure(): void
    {
        $collection = arr([
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Jane'],
            ['id' => 3, 'name' => 'Bob'],
        ]);

        $result = $collection->findKey(fn ($item) => $item['name'] === 'Jane');
        static::assertSame(1, $result);

        $result = $collection->findKey(fn ($item, $key) => $key === 2);
        static::assertSame(2, $result);

        $result = $collection->findKey(fn ($item) => $item['name'] === 'Alice');
        static::assertNull($result);
    }

    public function test_find_key_with_string_keys(): void
    {
        $collection = arr([
            'first' => 'value1',
            'second' => 'value2',
            'third' => 'value3',
        ]);

        static::assertSame('second', $collection->findKey('value2'));
        static::assertNull($collection->findKey('value4'));
    }

    public function test_find_key_with_null_values(): void
    {
        $collection = arr(['a', null, 'b', '']);

        static::assertSame(1, $collection->findKey(null));
        static::assertSame(1, $collection->findKey(''));
    }

    public function test_find_key_with_complex_closure(): void
    {
        $collection = arr([
            ['age' => 25, 'active' => true],
            ['age' => 30, 'active' => false],
            ['age' => 35, 'active' => true],
        ]);

        $result = $collection->findKey(function ($item) {
            return $item['age'] > 28 && $item['active'] === true;
        });

        static::assertSame(2, $result);
    }

    public function test_find_key_with_empty_array(): void
    {
        $collection = arr([]);

        static::assertNull($collection->findKey('anything'));
        static::assertNull($collection->findKey(fn () => true));
    }

    public function test_slice(): void
    {
        $collection = arr([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        static::assertSame(
            $collection->slice(0, 3)->values()->toArray(),
            [1, 2, 3],
        );

        static::assertSame(
            $collection->slice(3)->values()->toArray(),
            [4, 5, 6, 7, 8, 9, 10],
        );

        static::assertSame(
            $collection->slice(-3)->values()->toArray(),
            [8, 9, 10],
        );

        static::assertSame(
            $collection->slice(-3, 2)->values()->toArray(),
            [8, 9],
        );

        static::assertSame(
            $collection->slice(-3, -1)->values()->toArray(),
            [8, 9],
        );
    }

    public function test_every(): void
    {
        static::assertTrue(arr([])->every(fn (int $value) => ($value % 2) === 0));
        static::assertTrue(arr([2, 4, 6])->every(fn (int $value) => ($value % 2) === 0));
        static::assertFalse(arr([1, 2, 4, 6])->every(fn (int $value) => ($value % 2) === 0));
        static::assertTrue(arr([0, 1, true, false, ''])->every());
        static::assertFalse(arr([0, 1, true, false, '', null])->every());
    }

    public function test_append(): void
    {
        $collection = arr(['foo', 'bar']);

        static::assertSame(
            actual: $collection->append('foo')->toArray(),
            expected: ['foo', 'bar', 'foo'],
        );

        static::assertSame(
            actual: $collection->append(1, 'b')->toArray(),
            expected: ['foo', 'bar', 1, 'b'],
        );

        static::assertSame(
            actual: $collection->append(['a' => 'b'])->toArray(),
            expected: ['foo', 'bar', ['a' => 'b']],
        );
    }

    public function test_prepend(): void
    {
        $collection = arr(['foo', 'bar']);

        static::assertSame(
            actual: $collection->prepend('foo')->toArray(),
            expected: ['foo', 'foo', 'bar'],
        );

        static::assertSame(
            actual: $collection->prepend(1, 'b')->toArray(),
            expected: [1, 'b', 'foo', 'bar'],
        );

        static::assertSame(
            actual: $collection->prepend(['a' => 'b'])->toArray(),
            expected: [['a' => 'b'], 'foo', 'bar'],
        );
    }

    public function test_tap(): void
    {
        $collection = arr(['foo']);

        $log = [];
        $result = $collection->tap(function (ImmutableArray $array) use (&$log): void {
            $log[] = $array->first();
        });

        static::assertSame($collection, $result);
        static::assertEquals(['foo'], $log);
    }
}
