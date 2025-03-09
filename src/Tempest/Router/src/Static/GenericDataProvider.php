<?php

declare(strict_types=1);

namespace Tempest\Router\Static;

use Generator;
use Tempest\Router\DataProvider;

final readonly class GenericDataProvider implements DataProvider
{
    #[\Override]
    public function provide(): Generator
    {
        yield [];
    }
}
