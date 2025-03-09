<?php

declare(strict_types=1);

namespace Tempest\Database\Casters;

use Tempest\Mapper\Caster;

final class RelationCaster implements Caster
{
    #[\Override]
    public function cast(mixed $input): mixed
    {
        return $input;
    }

    #[\Override]
    public function serialize(mixed $input): string
    {
        return $input;
    }
}
