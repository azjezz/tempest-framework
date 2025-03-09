<?php

declare(strict_types=1);

namespace Tempest\Router\Session;

use Stringable;

final readonly class SessionId implements Stringable
{
    public function __construct(private string $id)
    {
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->id;
    }
}
