<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\Url;

/**
 * @internal
 */
final class UrlTest extends TestCase
{
    public function test_url(): void
    {
        $rule = new Url();

        static::assertFalse($rule->isValid('this is not a url'));
        static::assertFalse($rule->isValid('https://https://example.com'));
        static::assertTrue($rule->isValid('https://example.com'));
        static::assertTrue($rule->isValid('http://example.com'));
    }

    public function test_url_with_restricted_protocols(): void
    {
        $rule = new Url(['https']);

        static::assertFalse($rule->isValid('http://example.com'));
        static::assertTrue($rule->isValid('https://example.com'));
    }

    public function test_url_with_integer_value(): void
    {
        $rule = new Url();

        static::assertFalse($rule->isValid(1));
    }

    public function test_url_message(): void
    {
        $rule = new Url();

        static::assertSame('Value should be a valid URL', $rule->message());
    }
}
