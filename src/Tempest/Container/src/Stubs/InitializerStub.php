<?php

declare(strict_types=1);

namespace Tempest\Container\Stubs;

use Tempest\Container\Container;
use Tempest\Container\Initializer;

final class InitializerStub implements Initializer
{
    #[\Override]
    public function initialize(Container $container): mixed
    {
        return null;
    }
}
