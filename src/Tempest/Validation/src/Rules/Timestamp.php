<?php

declare(strict_types=1);

namespace Tempest\Validation\Rules;

use Attribute;
use Tempest\Validation\Rule;

#[Attribute]
final readonly class Timestamp implements Rule
{
    #[\Override]
    public function isValid(mixed $value): bool
    {
        if (! filter_var($value, FILTER_VALIDATE_INT)) {
            return false;
        }

        return (bool) date('Y-m-d H:i:s', $value);
    }

    #[\Override]
    public function message(): string
    {
        return 'Value should be a valid timestamp';
    }
}
