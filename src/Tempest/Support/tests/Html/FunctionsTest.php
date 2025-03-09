<?php

declare(strict_types=1);

namespace Tempest\Support\Tests\Html;

use PHPUnit\Framework\TestCase;
use Stringable;
use Tempest\Support\Html\HtmlString;
use Tempest\Support\Str\ImmutableString;
use Tempest\Support\Str\MutableString;

use function Tempest\Support\Html\create_tag;

/**
 * @internal
 */
final class FunctionsTest extends TestCase
{
    public function test_conversions(): void
    {
        static::assertInstanceOf(HtmlString::class, create_tag('div'));
        static::assertInstanceOf(Stringable::class, create_tag('div'));
        static::assertInstanceOf(MutableString::class, create_tag('div')->toMutableString());
        static::assertInstanceOf(ImmutableString::class, create_tag('div')->toImmutableString());
        static::assertSame('<div></div>', create_tag('div')->toString());
    }

    public function test_create_tag(): void
    {
        static::assertSame(
            expected: '<div></div>',
            actual: (string) create_tag('div'),
        );

        static::assertSame(
            expected: '<button type="submit">OK</button>',
            actual: (string) create_tag('button', ['type' => 'submit'], 'OK'),
        );

        static::assertSame(
            expected: '<a href="https://example.com">Link</a>',
            actual: (string) create_tag('a', ['href' => 'https://example.com'], 'Link'),
        );

        static::assertSame(
            expected: '<script src="https://example.com/script.js"></script>',
            actual: (string) create_tag('script', ['src' => 'https://example.com/script.js']),
        );

        static::assertSame(
            expected: '<link href="https://example.com/style.css" rel="stylesheet" />',
            actual: (string) create_tag('link', ['href' => 'https://example.com/style.css', 'rel' => 'stylesheet']),
        );

        static::assertSame(
            expected: '<img src="https://example.com/image.jpg" alt="An image" />',
            actual: (string) create_tag('img', ['src' => 'https://example.com/image.jpg', 'alt' => 'An image']),
        );

        static::assertSame(
            expected: '<input type="checkbox" checked />',
            actual: (string) create_tag('input', ['type' => 'checkbox', 'checked' => true]),
        );

        static::assertSame(
            expected: '<input type="checkbox" />',
            actual: (string) create_tag('input', ['type' => 'checkbox', 'checked' => false]),
        );
    }
}
