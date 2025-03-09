<?php

declare(strict_types=1);

namespace Tempest\Mapper\Casters;

use Tempest\Mapper\Caster;

final readonly class IntegerCaster implements Caster
{
    #[\Override]
    public function cast(mixed $input): int
    {
        return intval($input);
    }

    #[\Override]
    public function serialize(mixed $input): string
    {
        return (string) $input;
    }
}
