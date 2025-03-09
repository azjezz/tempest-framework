<?php

declare(strict_types=1);

namespace Tempest\Generation\Exceptions;

use Tempest\Console\Console;
use Tempest\Console\Exceptions\ConsoleException;

final class FileGenerationFailedException extends ConsoleException
{
    #[\Override]
    public function render(Console $console): void
    {
        $console->error($this->getMessage());
    }
}
