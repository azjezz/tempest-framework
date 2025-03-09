<?php

declare(strict_types=1);

namespace Tempest\Clock;

use DateTimeImmutable;

final class GenericClock implements Clock
{
    #[\Override]
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now');
    }

    #[\Override]
    public function time(): int
    {
        return hrtime(true);
    }

    #[\Override]
    public function sleep(int $seconds): void
    {
        sleep($seconds);
    }
}
