<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\RegEx;

/**
 * @internal
 */
final class RegexTest extends TestCase
{
    public function test_regex_rule(): void
    {
        $rule = new RegEx('/^[aA][bB]$/');

        static::assertSame(
            'The value must match the regular expression pattern: /^[aA][bB]$/',
            $rule->message(),
        );

        static::assertFalse($rule->isValid('cd'));
        static::assertFalse($rule->isValid('za'));

        static::assertTrue($rule->isValid('ab'));
        static::assertTrue($rule->isValid('AB'));
        static::assertTrue($rule->isValid('Ab'));
    }
}
