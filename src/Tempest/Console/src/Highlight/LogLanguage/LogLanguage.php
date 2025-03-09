<?php

declare(strict_types=1);

namespace Tempest\Console\Highlight\LogLanguage;

use Tempest\Console\Highlight\LogLanguage\Patterns\LogNamePattern;
use Tempest\Console\Highlight\LogLanguage\Patterns\LogTimestampPattern;
use Tempest\Highlight\Language;

final readonly class LogLanguage implements Language
{
    #[\Override]
    public function getName(): string
    {
        return 'log';
    }

    #[\Override]
    public function getAliases(): array
    {
        return [];
    }

    #[\Override]
    public function getInjections(): array
    {
        return [];
    }

    #[\Override]
    public function getPatterns(): array
    {
        return [
            new LogTimestampPattern(),
            new LogNamePattern(),
        ];
    }
}
