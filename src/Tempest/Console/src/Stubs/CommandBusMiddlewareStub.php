<?php

declare(strict_types=1);

namespace Tempest\Console\Stubs;

use Tempest\CommandBus\CommandBusMiddleware;
use Tempest\CommandBus\CommandBusMiddlewareCallable;

final class CommandBusMiddlewareStub implements CommandBusMiddleware
{
    #[\Override]
    public function __invoke(object $command, CommandBusMiddlewareCallable $next): void
    {
        $next($command);
    }
}
