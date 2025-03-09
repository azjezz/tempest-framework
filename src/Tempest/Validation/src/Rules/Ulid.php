<?php

declare(strict_types=1);

namespace Tempest\Validation\Rules;

use Attribute;
use Tempest\Validation\Rule;

#[Attribute]
final readonly class Ulid implements Rule
{
    #[\Override]
    public function isValid(mixed $value): bool
    {
        return preg_match('/^[0-9A-HJKMNP-TV-Z]{26}$/i', $value) === 1;
    }

    #[\Override]
    public function message(): string
    {
        return 'Value should be a valid ULID';
    }
}
