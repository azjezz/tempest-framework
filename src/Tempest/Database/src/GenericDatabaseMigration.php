<?php

declare(strict_types=1);

namespace Tempest\Database;

use Tempest\Database\QueryStatements\RawStatement;

final class GenericDatabaseMigration implements DatabaseMigration
{
    public string $name;

    public function __construct(
        private string $fileName,
        private string $content,
    ) {
        $this->name = $this->fileName;
    }

    #[\Override]
    public function up(): QueryStatement
    {
        return new RawStatement($this->content);
    }

    #[\Override]
    public function down(): ?QueryStatement
    {
        return null;
    }
}
