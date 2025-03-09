<?php

declare(strict_types=1);

namespace Tempest\Validation\Tests\Rules;

use PHPUnit\Framework\TestCase;
use Tempest\Validation\Rules\Enum;
use Tempest\Validation\Tests\Rules\Fixtures\SomeBackedEnum;
use Tempest\Validation\Tests\Rules\Fixtures\SomeEnum;
use UnexpectedValueException;

/**
 * @internal
 */
final class EnumTest extends TestCase
{
    public function test_validating_enums(): void
    {
        $rule = new Enum(SomeEnum::class);

        static::assertSame(
            sprintf(
                'The value must be a valid enumeration [%s] case',
                SomeEnum::class,
            ),
            $rule->message(),
        );

        static::assertFalse($rule->isValid('NOPE_NOT_HERE'));
        static::assertFalse($rule->isValid('NOPE_NOT_HERE_EITHER'));
        static::assertTrue($rule->isValid('VALUE_1'));
        static::assertTrue($rule->isValid('VALUE_2'));
    }

    public function test_validating_backed_enums(): void
    {
        $rule = new Enum(SomeBackedEnum::class);

        static::assertSame(
            sprintf(
                'The value must be a valid enumeration [%s] case',
                SomeBackedEnum::class,
            ),
            $rule->message(),
        );

        static::assertFalse($rule->isValid('three'));
        static::assertFalse($rule->isValid('four'));
        static::assertTrue($rule->isValid('one'));
        static::assertTrue($rule->isValid('two'));
    }

    public function test_enum_has_to_exist(): void
    {
        $this->expectExceptionObject(new UnexpectedValueException(
            sprintf(
                'The enum parameter must be a valid enum. Was given [%s].',
                'Bob',
            ),
        ));

        new Enum('Bob');
    }

    public function test_validating_only_enums(): void
    {
        $rule = new Enum(SomeEnum::class);
        static::assertTrue($rule->only(SomeEnum::VALUE_1)->isValid('VALUE_1'));
        static::assertFalse($rule->only(SomeEnum::VALUE_2)->isValid('VALUE_1'));
    }

    public function test_validating_except_enums(): void
    {
        $rule = new Enum(SomeEnum::class);
        static::assertTrue($rule->except(SomeEnum::VALUE_2)->isValid('VALUE_1'));
        static::assertFalse($rule->except(SomeEnum::VALUE_1)->isValid('VALUE_1'));
    }

    public function test_validating_only_backed_enums(): void
    {
        $rule = new Enum(SomeBackedEnum::class);
        static::assertTrue($rule->only(SomeBackedEnum::Test, SomeBackedEnum::Test2)->isValid('one'));
        static::assertTrue($rule->only(SomeBackedEnum::Test)->only(SomeBackedEnum::Test2)->isValid('one'));
        static::assertFalse($rule->only(SomeBackedEnum::Test2)->isValid('one'));
    }

    public function test_validating_except_backed_enums(): void
    {
        $rule = new Enum(SomeBackedEnum::class);
        static::assertTrue($rule->except(SomeBackedEnum::Test2)->isValid('one'));
        static::assertFalse($rule->except(SomeBackedEnum::Test)->isValid('one'));
    }
}
