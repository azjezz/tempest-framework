<?php

declare(strict_types=1);

namespace Tempest\Console\Highlight;

use Tempest\Highlight\TerminalTheme;
use Tempest\Highlight\Tokens\TokenType;

final readonly class TextTerminalTheme implements TerminalTheme
{
    #[\Override]
    public function before(TokenType $tokenType): string
    {
        return '';
    }

    #[\Override]
    public function after(TokenType $tokenType): string
    {
        return '';
    }

    #[\Override]
    public function escape(string $content): string
    {
        return $content;
    }
}
