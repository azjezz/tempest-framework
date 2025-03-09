<?php

declare(strict_types=1);

namespace Tempest\Console\Input;

use Exception;
use Tempest\Console\InputBuffer;

final readonly class UnsupportedInputBuffer implements InputBuffer
{
    #[\Override]
    public function read(int $bytes): string
    {
        throw new Exception('Unsupported');
    }

    #[\Override]
    public function readln(): string
    {
        throw new Exception('Unsupported');
    }
}
