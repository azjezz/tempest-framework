<?php

declare(strict_types=1);

namespace Tempest\Mapper\Mappers;

use Tempest\Mapper\Mapper;

final readonly class ArrayToJsonMapper implements Mapper
{
    #[\Override]
    public function canMap(mixed $from, mixed $to): bool
    {
        return false;
    }

    #[\Override]
    public function map(mixed $from, mixed $to): string
    {
        return json_encode($from);
    }
}
