<?php

declare(strict_types=1);

namespace Tempest\Support\Tests\Html;

use PHPUnit\Framework\TestCase;
use Stringable;
use Tempest\Support\Html\HtmlString;
use Tempest\Support\Str\ImmutableString;
use Tempest\Support\Str\MutableString;

/**
 * @internal
 */
final class HtmlStringTest extends TestCase
{
    public function test_conversions(): void
    {
        static::assertInstanceOf(MutableString::class, new HtmlString()->toMutableString());
        static::assertInstanceOf(ImmutableString::class, new HtmlString()->toImmutableString());
        static::assertSame('', new HtmlString()->toString());
    }

    public function test_create_from_tag(): void
    {
        static::assertSame(
            expected: '<div></div>',
            actual: (string) HtmlString::createTag('div'),
        );
    }
}
