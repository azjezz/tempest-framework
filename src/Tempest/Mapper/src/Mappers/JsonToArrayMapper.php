<?php

declare(strict_types=1);

namespace Tempest\Mapper\Mappers;

use Tempest\Mapper\Mapper;

final readonly class JsonToArrayMapper implements Mapper
{
    #[\Override]
    public function canMap(mixed $from, mixed $to): bool
    {
        return is_string($from) && json_validate($from);
    }

    #[\Override]
    public function map(mixed $from, mixed $to): array
    {
        return json_decode($from, true);
    }
}
