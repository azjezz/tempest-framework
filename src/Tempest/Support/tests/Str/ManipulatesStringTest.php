<?php

declare(strict_types=1);

namespace Tempest\Support\Str\Tests;

use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Tempest\Support\Str\ImmutableString;

use function Tempest\Support\arr;
use function Tempest\Support\str;

/**
 * @internal
 */
final class ManipulatesStringTest extends TestCase
{
    public function test_title(): void
    {
        static::assertTrue(str('jefferson costella')->title()->equals('Jefferson Costella'));
        static::assertTrue(str('jefFErson coSTella')->title()->equals('Jefferson Costella'));

        static::assertTrue(str()->title()->equals(''));
        static::assertTrue(str('123 tempest')->title()->equals('123 Tempest'));
        static::assertTrue(str('❤tempest')->title()->equals('❤Tempest'));
        static::assertTrue(str('tempest ❤')->title()->equals('Tempest ❤'));
        static::assertTrue(str('tempest123')->title()->equals('Tempest123'));
        static::assertTrue(str('Tempest123')->title()->equals('Tempest123'));

        $longString = 'lorem ipsum ' . str_repeat('dolor sit amet ', 1000);
        $expectedResult = 'Lorem Ipsum Dolor Sit Amet ' . str_repeat('Dolor Sit Amet ', 999);

        static::assertTrue(str($longString)->title()->equals($expectedResult));
    }

    public function test_deduplicate(): void
    {
        static::assertTrue(str('/some//odd//path/')->deduplicate('/')->equals('/some/odd/path/'));
        static::assertTrue(str(' tempest   php  framework ')->deduplicate()->equals(' tempest php framework '));
        static::assertTrue(str('whaaat')->deduplicate('a')->equals('what'));
        static::assertTrue(str('ムだだム')->deduplicate('だ')->equals('ムだム'));
    }

    public function test_pascal(): void
    {
        static::assertTrue(str()->pascal()->equals(''));
        static::assertTrue(str('foo bar')->pascal()->equals('FooBar'));
        static::assertTrue(str('foo - bar')->pascal()->equals('FooBar'));
        static::assertTrue(str('foo__bar')->pascal()->equals('FooBar'));
        static::assertTrue(str('_foo__bar')->pascal()->equals('FooBar'));
        static::assertTrue(str('-foo__bar')->pascal()->equals('FooBar'));
        static::assertTrue(str('fooBar')->pascal()->equals('FooBar'));
        static::assertTrue(str('foo_bar')->pascal()->equals('FooBar'));
        static::assertTrue(str('foo_bar1')->pascal()->equals('FooBar1'));
        static::assertTrue(str('1foo_bar')->pascal()->equals('1fooBar'));
        static::assertTrue(str('1foo_bar11')->pascal()->equals('1fooBar11'));
        static::assertTrue(str('1foo_1bar1')->pascal()->equals('1foo1bar1'));
        static::assertTrue(str('foo-barBaz')->pascal()->equals('FooBarBaz'));
        static::assertTrue(str('foo-bar_baz')->pascal()->equals('FooBarBaz'));
        static::assertSame('ÖffentlicheÜberraschungen', str('öffentliche-überraschungen')->pascal()->toString());
    }

    public function test_kebab(): void
    {
        static::assertTrue(str()->kebab()->equals(''));
        static::assertTrue(str('foo bar')->kebab()->equals('foo-bar'));
        static::assertTrue(str('foo - bar')->kebab()->equals('foo-bar'));
        static::assertTrue(str('foo__bar')->kebab()->equals('foo-bar'));
        static::assertTrue(str('_foo__bar')->kebab()->equals('foo-bar'));
        static::assertTrue(str('-foo__bar')->kebab()->equals('foo-bar'));
        static::assertTrue(str('fooBar')->kebab()->equals('foo-bar'));
        static::assertTrue(str('foo_bar')->kebab()->equals('foo-bar'));
        static::assertTrue(str('foo_bar1')->kebab()->equals('foo-bar1'));
        static::assertTrue(str('1foo_bar')->kebab()->equals('1foo-bar'));
        static::assertTrue(str('1foo_bar11')->kebab()->equals('1foo-bar11'));
        static::assertTrue(str('1foo_1bar1')->kebab()->equals('1foo-1bar1'));
        static::assertTrue(str('foo-barBaz')->kebab()->equals('foo-bar-baz'));
        static::assertTrue(str('foo-bar_baz')->kebab()->equals('foo-bar-baz'));
    }

    public function test_snake(): void
    {
        static::assertTrue(str('')->snake()->equals(''));
        static::assertTrue(str('foo bar')->snake()->equals('foo_bar'));
        static::assertTrue(str('foo - bar')->snake()->equals('foo_bar'));
        static::assertTrue(str('foo__bar')->snake()->equals('foo_bar'));
        static::assertTrue(str('_foo__bar')->snake()->equals('foo_bar'));
        static::assertTrue(str('-foo__bar')->snake()->equals('foo_bar'));
        static::assertTrue(str('fooBar')->snake()->equals('foo_bar'));
        static::assertTrue(str('foo_bar')->snake()->equals('foo_bar'));
        static::assertTrue(str('foo_bar1')->snake()->equals('foo_bar1'));
        static::assertTrue(str('1foo_bar')->snake()->equals('1foo_bar'));
        static::assertTrue(str('1foo_bar11')->snake()->equals('1foo_bar11'));
        static::assertTrue(str('1foo_1bar1')->snake()->equals('1foo_1bar1'));
        static::assertTrue(str('foo-barBaz')->snake()->equals('foo_bar_baz'));
        static::assertTrue(str('foo-bar_baz')->snake()->equals('foo_bar_baz'));
    }

    #[TestWith([0])]
    #[TestWith([16])]
    #[TestWith([100])]
    public function test_random(int $length): void
    {
        static::assertEquals($length, str()->random($length)->length());
    }

    public function test_finish(): void
    {
        static::assertTrue(str('foo')->finish('/')->equals('foo/'));
        static::assertTrue(str('foo/')->finish('/')->equals('foo/'));
        static::assertTrue(str('abbcbc')->finish('bc')->equals('abbc'));
        static::assertTrue(str('abcbbcbc')->finish('bc')->equals('abcbbc'));
    }

    public function test_format(): void
    {
        static::assertTrue(str('%sfoo%s')->format('[', ']')->equals('[foo]'));
    }

    public function test_str_after_first(): void
    {
        static::assertTrue(str('hannah')->afterFirst('han')->equals('nah'));
        static::assertTrue(str('hannah')->afterFirst(str('han'))->equals('nah'));
        static::assertTrue(str('hannah')->afterFirst('n')->equals('nah'));
        static::assertTrue(str('ééé hannah')->afterFirst('han')->equals('nah'));
        static::assertTrue(str('hannah')->afterFirst('xxxx')->equals('hannah'));
        static::assertTrue(str('hannah')->afterFirst('')->equals('hannah'));
        static::assertTrue(str('han0nah')->afterFirst('0')->equals('nah'));
        static::assertTrue(str('han2nah')->afterFirst('2')->equals('nah'));
        static::assertTrue(str('@foo@bar.com')->afterFirst(['@', '.'])->equals('foo@bar.com'));
        static::assertTrue(str('foo@bar.com')->afterFirst(['@', '.'])->equals('bar.com'));
        static::assertTrue(str('foobar.com')->afterFirst(['@', '.'])->equals('com'));
    }

    public function test_str_after_last(): void
    {
        static::assertTrue(str('yvette')->afterLast('yve')->equals('tte'));
        static::assertTrue(str('yvette')->afterLast(str('yve'))->equals('tte'));
        static::assertTrue(str('yvette')->afterLast('t')->equals('e'));
        static::assertTrue(str('ééé yvette')->afterLast('t')->equals('e'));
        static::assertTrue(str('yvette')->afterLast('tte')->equals(''));
        static::assertTrue(str('yvette')->afterLast('xxxx')->equals('yvette'));
        static::assertTrue(str('yvette')->afterLast('')->equals('yvette'));
        static::assertTrue(str('yv0et0te')->afterLast('0')->equals('te'));
        static::assertTrue(str('yv2et2te')->afterLast('2')->equals('te'));
        static::assertTrue(str('----foo')->afterLast('---')->equals('foo'));
        static::assertTrue(str('@foo@bar.com')->afterLast(['@', '.'])->equals('com'));
    }

    public function test_str_between(): void
    {
        static::assertTrue(str('abc')->between('', 'c')->equals('abc'));
        static::assertTrue(str('abc')->between('a', '')->equals('abc'));
        static::assertTrue(str('abc')->between('', '')->equals('abc'));
        static::assertTrue(str('abc')->between('a', 'c')->equals('b'));
        static::assertTrue(str('abc')->between(str('a'), str('c'))->equals('b'));
        static::assertTrue(str('dddabc')->between('a', 'c')->equals('b'));
        static::assertTrue(str('abcddd')->between('a', 'c')->equals('b'));
        static::assertTrue(str('dddabcddd')->between('a', 'c')->equals('b'));
        static::assertTrue(str('hannah')->between('ha', 'ah')->equals('nn'));
        static::assertTrue(str('[a]ab[b]')->between('[', ']')->equals('a]ab[b'));
        static::assertTrue(str('foofoobar')->between('foo', 'bar')->equals('foo'));
        static::assertTrue(str('foobarbar')->between('foo', 'bar')->equals('bar'));
        static::assertTrue(str('12345')->between('1', '5')->equals('234'));
        static::assertTrue(str('123456789')->between('123', '6789')->equals('45'));
        static::assertTrue(str('nothing')->between('foo', 'bar')->equals('nothing'));
    }

    public function test_str_before(): void
    {
        static::assertTrue(str('hannah')->before('nah')->equals('han'));
        static::assertTrue(str('hannah')->before(str('nah'))->equals('han'));
        static::assertTrue(str('hannah')->before('n')->equals('ha'));
        static::assertTrue(str('ééé hannah')->before('han')->equals('ééé '));
        static::assertTrue(str('hannah')->before('xxxx')->equals('hannah'));
        static::assertTrue(str('hannah')->before('')->equals('hannah'));
        static::assertTrue(str('han0nah')->before('0')->equals('han'));
        static::assertTrue(str('han2nah')->before('2')->equals('han'));
        static::assertTrue(str('')->before('')->equals(''));
        static::assertTrue(str('')->before('a')->equals(''));
        static::assertTrue(str('a')->before('a')->equals(''));
        static::assertTrue(str('foo@bar.com')->before('@')->equals('foo'));
        static::assertTrue(str('foo@@bar.com')->before('@')->equals('foo'));
        static::assertTrue(str('@foo@bar.com')->before('@')->equals(''));
        static::assertTrue(str('foo@bar.com')->before(['@', '.'])->equals('foo'));
        static::assertTrue(str('@foo@bar.com')->before(['@', '.'])->equals(''));
    }

    public function test_str_before_last(): void
    {
        static::assertTrue(str('yvette')->beforeLast('tte')->equals('yve'));
        static::assertTrue(str('yvette')->beforeLast(str('tte'))->equals('yve'));
        static::assertTrue(str('yvette')->beforeLast('t')->equals('yvet'));
        static::assertTrue(str('ééé yvette')->beforeLast('yve')->equals('ééé '));
        static::assertTrue(str('yvette')->beforeLast('yve')->equals(''));
        static::assertTrue(str('yvette')->beforeLast('xxxx')->equals('yvette'));
        static::assertTrue(str('yvette')->beforeLast('')->equals('yvette'));
        static::assertTrue(str('yv0et0te')->beforeLast('0')->equals('yv0et'));
        static::assertTrue(str('yv2et2te')->beforeLast('2')->equals('yv2et'));
        static::assertTrue(str('')->beforeLast('test')->equals(''));
        static::assertTrue(str('yvette')->beforeLast('yvette')->equals(''));
        static::assertTrue(str('tempest framework')->beforeLast(' ')->equals('tempest'));
        static::assertTrue(str("yvette\tyv0et0te")->beforeLast("\t")->equals('yvette'));
        static::assertTrue(str('This is Tempest.')->beforeLast([' ', '.'])->equals('This is Tempest'));
        static::assertTrue(str('This is Tempest')->beforeLast([' ', '.'])->equals('This is'));
    }

    public function test_starts_with(): void
    {
        static::assertTrue(str('abc')->startsWith('a'));
        static::assertTrue(str('abc')->startsWith(str('a')));
        static::assertFalse(str('abc')->startsWith('c'));
    }

    public function test_ends_with(): void
    {
        static::assertTrue(str('abc')->endsWith('c'));
        static::assertTrue(str('abc')->endsWith(str('c')));
        static::assertFalse(str('abc')->endsWith('a'));
    }

    public function test_replace(): void
    {
        static::assertTrue(str('foo bar')->replace('bar', 'baz')->equals('foo baz'));
        static::assertTrue(str('foo bar')->replace(str('bar'), 'baz')->equals('foo baz'));
        static::assertTrue(str('foo bar')->replace('bar', str('baz'))->equals('foo baz'));
        static::assertTrue(str('jon doe')->replace(['jon', 'jane'], 'luke')->equals('luke doe'));
        static::assertTrue(str('jon doe')->replace(['jon', 'jane', 'doe'], ['Jon', 'Jane', 'Doe'])->equals('Jon Doe'));
        static::assertTrue(
            str('jon doe')->replace(['jon', 'jane', 'doe'], '<censored>')->equals('<censored> <censored>'),
        );
    }

    public function test_erase(): void
    {
        static::assertTrue(str('foo bar')->erase('bar')->equals('foo '));
        static::assertTrue(str('foo bar')->erase('')->equals('foo bar'));
    }

    public function test_replace_last(): void
    {
        static::assertTrue(str('foobar foobar')->replaceLast('bar', 'qux')->equals('foobar fooqux'));
        static::assertTrue(str('foo/bar? foo/bar?')->replaceLast('bar?', 'qux?')->equals('foo/bar? foo/qux?'));
        static::assertTrue(str('foobar foobar')->replaceLast('bar', '')->equals('foobar foo'));
        static::assertTrue(str('foobar foobar')->replaceLast('xxx', 'yyy')->equals('foobar foobar'));
        static::assertTrue(str('foobar foobar')->replaceLast('', 'yyy')->equals('foobar foobar'));
        static::assertTrue(str('Malmö Jönköping')->replaceLast('ö', 'xxx')->equals('Malmö Jönkxxxping'));
        static::assertTrue(str('Malmö Jönköping')->replaceLast('', 'yyy')->equals('Malmö Jönköping'));
    }

    public function test_replace_first(): void
    {
        static::assertTrue(str('foobar foobar')->replaceFirst('bar', 'qux')->equals('fooqux foobar'));
        static::assertTrue(str('foo/bar? foo/bar?')->replaceFirst('bar?', 'qux?')->equals('foo/qux? foo/bar?'));
        static::assertTrue(str('foobar foobar')->replaceFirst('bar', '')->equals('foo foobar'));
        static::assertTrue(str('foobar foobar')->replaceFirst('xxx', 'yyy')->equals('foobar foobar'));
        static::assertTrue(str('foobar foobar')->replaceFirst('', 'yyy')->equals('foobar foobar'));
        static::assertTrue(str('Jönköping Malmö')->replaceFirst('ö', 'xxx')->equals('Jxxxnköping Malmö'));
        static::assertTrue(str('Jönköping Malmö')->replaceFirst('', 'yyy')->equals('Jönköping Malmö'));
    }

    public function test_replace_end(): void
    {
        static::assertTrue(str('foobar fooqux')->replaceEnd('bar', 'qux')->equals('foobar fooqux'));
        static::assertTrue(str('foo/bar? foo/qux?')->replaceEnd('bar?', 'qux?')->equals('foo/bar? foo/qux?'));
        static::assertTrue(str('foobar foo')->replaceEnd('bar', '')->equals('foobar foo'));
        static::assertTrue(str('foobar foobar')->replaceEnd('xxx', 'yyy')->equals('foobar foobar'));
        static::assertTrue(str('foobar foobar')->replaceEnd('', 'yyy')->equals('foobar foobar'));
        static::assertTrue(str('fooxxx foobar')->replaceEnd('xxx', 'yyy')->equals('fooxxx foobar'));
        static::assertTrue(str('Malmö Jönköping')->replaceEnd('ö', 'xxx')->equals('Malmö Jönköping'));
        static::assertTrue(str('Malmö Jönköping')->replaceEnd('öping', 'yyy')->equals('Malmö Jönkyyy'));
    }

    public function test_append(): void
    {
        static::assertTrue(str('foo')->append('bar')->equals('foobar'));
        static::assertTrue(str('foo')->append('bar', 'baz')->equals('foobarbaz'));
        static::assertTrue(str('foo')->append(str('bar'), str('baz'))->equals('foobarbaz'));
    }

    public function test_prepend(): void
    {
        static::assertTrue(str('bar')->prepend('foo')->equals('foobar'));
        static::assertTrue(str('baz')->prepend('bar', 'foo')->equals('barfoobaz'));
        static::assertTrue(str('baz')->prepend(str('bar'), str('foo'))->equals('barfoobaz'));
    }

    public function test_match(): void
    {
        $match = str('10-abc')->match('/(?<id>\d+-)/')['id'];

        static::assertSame('10-', $match);
    }

    public function test_matches(): void
    {
        static::assertTrue(str('10-abc')->matches('/(?<id>\d+-)/'));
        static::assertTrue(str('10-abc')->matches('/(\d+-)/'));
        static::assertTrue(str('10-abc')->matches('/\d+-/'));
        static::assertFalse(str('10abc')->matches('/\d+-/'));
        static::assertFalse(str('abc')->matches('/\d+-/'));
    }

    public function test_replace_regex(): void
    {
        static::assertTrue(str('10-abc')->replaceRegex('/(?<id>\d+-)/', '')->equals('abc'));
        static::assertTrue(str('10-abc')->replaceRegex('/(?<id>\d+-)/', fn () => '')->equals('abc'));
        static::assertTrue(str('10-abc')->replaceRegex(['/\d/', '/\w/'], ['#', 'X'])->equals('##-XXX'));
    }

    public function test_match_all(): void
    {
        // Test for Simple Pattern
        $regex = '/Hello/';
        $matches = str('Hello world, Hello universe')->matchAll($regex);
        $expected = [['Hello', 'Hello']];
        static::assertSame($expected, $matches);

        // Test for Named Capture Groups
        $regex = '/(?<adjective>quick|lazy) (?<noun>brown|dog)/';
        $matches = str('The quick brown fox, then the lazy dog')->matchAll($regex);
        $expectedAdjectives = [
            [
                'quick brown',
                'lazy dog',
            ],
            'adjective' => [
                'quick',
                'lazy',
            ],
            1 => [
                'quick',
                'lazy',
            ],
            'noun' => [
                'brown',
                'dog',
            ],
            2 => [
                'brown',
                'dog',
            ],
        ];

        static::assertSame($expectedAdjectives, $matches);

        // Test for No Matches
        $regex = '/cat/';
        $matches = str('The quick brown fox, then the lazy dog')->matchAll($regex);
        $expected = [];
        static::assertSame($expected, $matches);

        // Test for Mixed Captures
        $regex = '/(?<adjective>quick|lazy) (?<noun>brown|dog) (?<action>jumps|eats)?/';
        $matches = str('The quick brown fox, then the lazy dog eats')->matchAll($regex);
        $expected = [
            [
                'quick brown ',
                'lazy dog eats',
            ],
            'adjective' => [
                'quick',
                'lazy',
            ],
            [
                'quick',
                'lazy',
            ],
            'noun' => [
                'brown',
                'dog',
            ],
            [
                'brown',
                'dog',
            ],
            'action' => [
                '',
                'eats',
            ],
            [
                '',
                'eats',
            ],
        ];
        static::assertSame($expected, $matches);

        // Test flags
        $regex = '/(foo)(bar)/';
        $matches = str('foobarbaz')->matchAll($regex, PREG_OFFSET_CAPTURE);
        $expected = [
            [
                [
                    'foobar',
                    0,
                ],
            ],
            [
                [
                    'foo',
                    0,
                ],
            ],
            [
                [
                    'bar',
                    3,
                ],
            ],
        ];
        static::assertSame($expected, $matches);

        $regex = '/^def/';
        $matches = str('abcdef')->matchAll(regex: $regex, offset: 3);
        $expected = [];
        static::assertSame($expected, $matches);
    }

    public function test_explode(): void
    {
        static::assertTrue(str('path/to/tempest')->explode('/')->equals(['path', 'to', 'tempest']));
        static::assertTrue(str('john doe')->explode()->equals(['john', 'doe']));
    }

    public function test_implode(): void
    {
        static::assertSame('path/to/tempest', ImmutableString::implode(['path', 'to', 'tempest'], '/')->toString());
        static::assertSame('john doe', ImmutableString::implode(['john', 'doe'])->toString());
        static::assertSame('path/to/tempest', ImmutableString::implode(arr(['path', 'to', 'tempest']), '/')->toString());
        static::assertSame('john doe', ImmutableString::implode(arr(['john', 'doe']))->toString());
    }

    #[TestWith([['Jon', 'Jane'], 'Jon and Jane'])]
    #[TestWith([['Jon', 'Jane', 'Jill'], 'Jon, Jane and Jill'])]
    public function test_join(array $initial, string $expected): void
    {
        static::assertEquals($expected, ImmutableString::join($initial));
    }

    #[TestWith([['Jon', 'Jane'], ', ', ' and maybe ', 'Jon and maybe Jane'])]
    #[TestWith([['Jon', 'Jane', 'Jill'], ' + ', ' and ', 'Jon + Jane and Jill'])]
    #[TestWith([['Jon', 'Jane', 'Jill'], ' + ', null, 'Jon + Jane + Jill'])]
    public function test_join_with_glues(array $initial, string $glue, ?string $finalGlue, string $expected): void
    {
        static::assertTrue(ImmutableString::join($initial, $glue, $finalGlue)->equals($expected));
    }

    public function test_excerpt(): void
    {
        $content = str('a
b
c
d
e
f
g');

        static::assertTrue($content->excerpt(2, 4)->equals('b
c
d'));

        static::assertTrue($content->excerpt(-10, 2)->equals('a
b'));

        static::assertTrue($content->excerpt(7, 100)->equals('g'));

        static::assertSame([2 => 'b', 3 => 'c', 4 => 'd'], $content->excerpt(2, 4, asArray: true)->toArray());
    }

    public function test_wrap(): void
    {
        static::assertSame('Leon Scott Kennedy', str('Scott')->wrap(before: 'Leon ', after: ' Kennedy')->toString());
        static::assertSame('"value"', str('value')->wrap('"')->toString());
    }

    public function test_unwrap(): void
    {
        static::assertSame('Scott', str('Leon Scott Kennedy')->unwrap(before: 'Leon ', after: ' Kennedy')->toString());
        static::assertSame('value', str('"value"')->unwrap('"')->toString());
        static::assertSame('"value"', str('"value"')->unwrap('`')->toString());
        static::assertSame('[value', str('[value')->unwrap('[', ']')->toString());
        static::assertEquals('some: "json"', str('{some: "json"}')->unwrap('{', '}')->toString());

        static::assertSame('value', str('[value')->unwrap('[', ']', strict: false)->toString());
        static::assertSame('value', str('value]')->unwrap('[', ']', strict: false)->toString());
        static::assertSame('Scott', str('Scott Kennedy')->unwrap(before: 'Leon ', after: ' Kennedy', strict: false)->toString());
    }

    public function test_start(): void
    {
        static::assertSame('Leon Scott Kennedy', str('Scott Kennedy')->start('Leon ')->toString());
        static::assertSame('Leon Scott Kennedy', str('Leon Scott Kennedy')->start('Leon ')->toString());
    }

    public function test_limit(): void
    {
        static::assertSame('Lorem', str('Lorem ipsum')->truncate(5)->toString());
        static::assertSame('Lorem...', str('Lorem ipsum')->truncate(5, end: '...')->toString());
        static::assertSame('...', str('Lorem ipsum')->truncate(0, end: '...')->toString());
        static::assertSame('L...', str('Lorem ipsum')->truncate(1, end: '...')->toString());
        static::assertSame('Lorem ipsum', str('Lorem ipsum')->truncate(100)->toString());
        static::assertSame('Lorem ipsum', str('Lorem ipsum')->truncate(100, end: '...')->toString());
    }

    public function test_substr(): void
    {
        static::assertSame('Lorem', str('Lorem ipsum')->substr(0, length: 5)->toString());
        static::assertSame('ipsum', str('Lorem ipsum')->substr(6, length: 5)->toString());
        static::assertSame('ipsum', str('Lorem ipsum')->substr(6)->toString());
        static::assertSame('ipsum', str('Lorem ipsum')->substr(-5)->toString());
        static::assertSame('ipsum', str('Lorem ipsum')->substr(-5, length: 5)->toString());
    }

    public function test_take(): void
    {
        // positive
        static::assertSame('Lorem', str('Lorem ipsum')->take(5)->toString());
        static::assertSame('Lorem ipsum', str('Lorem ipsum')->take(100)->toString());

        // negative
        static::assertSame('ipsum', str('Lorem ipsum')->take(-5)->toString());
    }

    public function test_chunk(): void
    {
        static::assertTrue(str(PHP_EOL)->chunk(100)->equals([PHP_EOL]));
        static::assertTrue(str('')->chunk(1)->equals(['']));
        static::assertTrue(str('123')->chunk(-1)->equals([]));
        static::assertTrue(str('123')->chunk(1)->equals(['1', '2', '3']));
        static::assertTrue(str('123')->chunk(1000)->equals(['123']));
        static::assertTrue(str('foobarbaz')->chunk(3)->equals(['foo', 'bar', 'baz']));
        static::assertTrue(str('foobarbaz22')->chunk(3)->equals(['foo', 'bar', 'baz', '22']));
    }

    public function test_insert_at(): void
    {
        static::assertSame('foo', str()->insertAt(0, 'foo')->toString());
        static::assertSame('foo', str()->insertAt(-1, 'foo')->toString());
        static::assertSame('foo', str()->insertAt(100, 'foo')->toString());
        static::assertSame('foo', str()->insertAt(-100, 'foo')->toString());
        static::assertSame('foobar', str('bar')->insertAt(0, 'foo')->toString());
        static::assertSame('barfoo', str('bar')->insertAt(3, 'foo')->toString());
        static::assertSame('foobarbaz', str('foobaz')->insertAt(3, 'bar')->toString());
        static::assertSame('123', str('13')->insertAt(-1, '2')->toString());
    }

    public function test_replace_at(): void
    {
        static::assertSame('foobar', str('foo2bar')->replaceAt(4, -1, '')->toString());
        static::assertSame('foobar', str('foo2bar')->replaceAt(3, 1, '')->toString());
        static::assertSame('fooquxbar', str('foo2bar')->replaceAt(3, 1, 'qux')->toString());
        static::assertSame('foobarbaz', str('barbaz')->replaceAt(0, 0, 'foo')->toString());
        static::assertSame('barbazfoo', str('barbaz')->replaceAt(6, 0, 'foo')->toString());
        static::assertSame('bar', str('foo')->replaceAt(0, 3, 'bar')->toString());
        static::assertSame('abc1', str('abcd')->replaceAt(-1, 1, '1')->toString());
        static::assertSame('ab1d', str('abcd')->replaceAt(-1, -1, '1')->toString());
        static::assertSame('abc', str('abc')->replaceAt(3, 1, '')->toString());
    }

    public function test_strip_tags(): void
    {
        static::assertSame('Hello World', str('<p>Hello World</p>')->stripTags()->toString());
        static::assertSame('Hello World', str('<p>Hello <strong>World</strong></p>')->stripTags()->toString());
        static::assertSame('Hello <strong>World</strong>', str('<p>Hello <strong>World</strong></p>')->stripTags(allowed: '<strong>')->toString());
        static::assertSame('<p>Hello World</p>', str('<p>Hello <strong>World</strong></p>')->stripTags(allowed: '<p>')->toString());

        static::assertSame('Hello <strong>World</strong>', str('<p>Hello <strong>World</strong></p>')->stripTags(allowed: 'strong')->toString());
        static::assertSame('<p>Hello World</p>', str('<p>Hello <strong>World</strong></p>')->stripTags(allowed: 'p')->toString());
    }

    public function test_align_center(): void
    {
        static::assertSame('  foo  ', str('foo')->alignCenter(7)->toString());
        static::assertSame('  foo  ', str(' foo ')->alignCenter(7)->toString());
        static::assertSame('   foo    ', str('foo')->alignCenter(10)->toString());

        static::assertSame('  foo  ', str('foo')->alignCenter(2, padding: 2)->toString());
        static::assertSame('   foo    ', str('foo')->alignCenter(10, padding: 2)->toString());
        static::assertSame('  foo  ', str(' foo ')->alignCenter(2, padding: 2)->toString());
    }

    public function test_align_right(): void
    {
        static::assertSame('foo', str('foo')->alignRight(3)->toString());
        static::assertSame('       foo', str('foo')->alignRight(10)->toString());
        static::assertSame('       foo', str(' foo')->alignRight(10)->toString());
        static::assertSame('     foo  ', str(' foo')->alignRight(10, padding: 2)->toString());
        static::assertSame('  foo  ', str('foo')->alignRight(2, padding: 2)->toString());
    }

    public function test_align_left(): void
    {
        static::assertSame('foo', str('foo')->alignLeft(3)->toString());
        static::assertSame('foo       ', str('foo')->alignLeft(10)->toString());
        static::assertSame('foo       ', str(' foo')->alignLeft(10)->toString());
        static::assertSame('  foo     ', str(' foo')->alignLeft(10, padding: 2)->toString());
        static::assertSame('  foo  ', str('foo')->alignLeft(2, padding: 2)->toString());
    }

    public function test_contains(): void
    {
        static::assertTrue(str('foo')->contains('fo'));
        static::assertFalse(str('foo')->contains('bar'));
    }

    public function test_levenshtein(): void
    {
        static::assertSame(0, str('foo')->levenshtein('foo'));
        static::assertSame(3, str('foo')->levenshtein('bar'));
    }

    public function test_is_empty(): void
    {
        static::assertTrue(str('')->isEmpty());
        static::assertFalse(str('a')->isEmpty());
    }

    public function test_is_not_empty(): void
    {
        static::assertTrue(str('a')->isNotEmpty());
        static::assertFalse(str('')->isNotEmpty());
    }

    public function test_reverse(): void
    {
        static::assertSame('oof', str('foo')->reverse()->toString());
        static::assertSame('…oof', str('foo…')->reverse()->toString());
    }

    public function test_truncate_start(): void
    {
        static::assertSame('Lorem ipsum', str('Lorem ipsum')->truncateStart(20, start: '…')->toString());
        static::assertSame('…ipsum', str('Lorem ipsum')->truncateStart(5, start: '…')->toString());
        static::assertSame('…', str('Lorem ipsum')->truncateStart(0, start: '…')->toString());
        static::assertSame('…orem ipsum', str('Lorem ipsum')->truncateStart(-1, start: '…')->toString());
    }

    public function test_tap(): void
    {
        $string = str('foo');

        $log = '';
        $result = $string->tap(function (ImmutableString $string) use (&$log): void {
            $log .= $string->toString();
        });

        static::assertSame($string, $result);
        static::assertEquals('foo', $log);
    }
}
