<?php

declare(strict_types=1);

namespace Tempest\Console\Highlight\VarExportLanguage;

use Tempest\Console\Highlight\VarExportLanguage\Patterns\VarExportTagPattern;
use Tempest\Highlight\Language;

final readonly class VarExportLanguage implements Language
{
    #[\Override]
    public function getName(): string
    {
        return 'varexport';
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
            new VarExportTagPattern(),
        ];
    }
}
