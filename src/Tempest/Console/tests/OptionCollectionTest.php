<?php

declare(strict_types=1);

namespace Tempest\Console\Tests;

use PHPUnit\Framework\TestCase;
use Tempest\Console\Components\Option;
use Tempest\Console\Components\OptionCollection;

/**
 * @internal
 */
final class OptionCollectionTest extends TestCase
{
    public function test_filter(): void
    {
        $options = new OptionCollection(['foo', 'bar', 'baz']);

        $options->filter('ba');
        static::assertCount(2, $options->getOptions());
        static::assertSame('bar', $options->getActive()->value);

        $options->filter(null);
        static::assertCount(3, $options->getOptions());
        static::assertSame('bar', $options->getActive()->value);

        $options->filter('bar');
        static::assertCount(1, $options->getOptions());
        static::assertSame('bar', $options->getOptions()->first()->value);

        $options->filter('ergljherkigjerg');
        static::assertCount(0, $options->getOptions());
        static::assertSame(null, $options->getActive());
    }

    public function test_keeps_active_on_filter(): void
    {
        $options = new OptionCollection(['foo', 'bar', 'baz']);

        $options->next();
        $options->next();
        static::assertSame('baz', $options->getActive()->value);

        $options->filter('ba');
        static::assertCount(2, $options->getOptions());
        static::assertSame('baz', $options->getActive()->value);

        $options->filter('baz');
        static::assertSame('baz', $options->getActive()->value);

        $options->filter('bazz');
        static::assertSame(null, $options->getActive());
    }

    public function test_navigate(): void
    {
        $options = new OptionCollection(['foo', 'bar', 'baz']);

        $options->next();
        static::assertSame('bar', $options->getActive()->value);

        $options->next();
        static::assertSame('baz', $options->getActive()->value);

        $options->next();
        static::assertSame('foo', $options->getActive()->value);

        $options->previous();
        static::assertSame('baz', $options->getActive()->value);

        $options->previous();
        static::assertSame('bar', $options->getActive()->value);

        $options->previous();
        static::assertSame('foo', $options->getActive()->value);
    }

    public function test_select(): void
    {
        $options = new OptionCollection(['foo', 'bar', 'baz']);

        $options->next();
        $options->toggleCurrent();
        static::assertSame(['bar'], $this->toValues($options->getSelectedOptions()));

        $options->toggleCurrent();
        static::assertSame([], $this->toValues($options->getSelectedOptions()));

        $options->toggleCurrent();
        $options->next();
        $options->toggleCurrent();
        static::assertSame(['bar', 'baz'], $this->toValues($options->getSelectedOptions()));
    }

    public function test_select_and_filter(): void
    {
        $options = new OptionCollection(['foo', 'bar', 'baz']);

        $options->toggleCurrent();
        $options->next();
        $options->toggleCurrent();
        $options->next();
        $options->toggleCurrent();
        static::assertSame(['foo', 'bar', 'baz'], $this->toValues($options->getSelectedOptions()));

        $options->filter('ba');
        static::assertSame(['bar', 'baz'], $this->toValues($options->getSelectedOptions()));

        $options->filter(null);
        static::assertSame(['bar', 'baz'], $this->toValues($options->getSelectedOptions()));

        $options->filter('r');
        static::assertSame(['bar'], $this->toValues($options->getSelectedOptions()));
        static::assertSame('bar', $options->current()->value);
    }

    public function test_scrollable_section(): void
    {
        $options = new OptionCollection(['foo', 'bar', 'baz', 'qux', 'quux']);

        static::assertCount(2, $options->getScrollableSection(1, 2));
        static::assertSame(['bar', 'baz'], $this->toValues($options->getScrollableSection(1, 2)));
    }

    public function test_enum_options(): void
    {
        $options = new OptionCollection(OptionCollectionEnum::cases());

        $options->next();
        static::assertSame('OPT_2', $options->getActive()->displayValue);

        $options->next();
        static::assertSame('OPT_3', $options->getActive()->displayValue);

        $options->next();
        static::assertSame('OPT_1', $options->getActive()->displayValue);
    }

    public function test_set_active_list(): void
    {
        $options = new OptionCollection(['foo', 'bar', 'baz', 'qux', 'quux']);

        $options->setActive('qux');

        static::assertSame('qux', $options->getActive()->value);
    }

    public function test_set_active_assoc(): void
    {
        $options = new OptionCollection(['foo' => 'Foo', 'bar' => 'Bar', 'baz' => 'Baz']);

        $options->setActive('bar');
        static::assertSame('Bar', $options->getActive()->value);

        $options->setActive('Baz');
        static::assertSame('Baz', $options->getActive()->value);
    }

    public function test_set_active_enum(): void
    {
        $options = new OptionCollection(OptionCollectionEnum::cases());

        $options->setActive(OptionCollectionEnum::OPT_2);

        static::assertSame(OptionCollectionEnum::OPT_2, $options->getActive()->value);
    }

    private function toValues(array $options): array
    {
        return array_map(fn (Option $option) => $option->value, array_values($options));
    }
}
