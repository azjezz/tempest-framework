<?php

declare(strict_types=1);

namespace Tempest\Validation\Rules;

use Attribute;
use Tempest\Validation\Rule;

#[Attribute]
final readonly class Numeric implements Rule
{
    #[\Override]
    public function isValid(mixed $value): bool
    {
        return boolval(preg_match('/^[0-9]+$/', $value));
    }

    #[\Override]
    public function message(): string
    {
        return 'Value should only contain numeric characters';
    }
}
