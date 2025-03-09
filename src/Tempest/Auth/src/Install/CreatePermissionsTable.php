<?php

declare(strict_types=1);

namespace Tempest\Auth\Install;

use Tempest\Database\DatabaseMigration;
use Tempest\Database\QueryStatements\CreateTableStatement;
use Tempest\Database\QueryStatements\DropTableStatement;
use Tempest\Discovery\DoNotDiscover;

#[DoNotDiscover]
final class CreatePermissionsTable implements DatabaseMigration
{
    private(set) string $name = '0000-00-01_create_permissions_table';

    #[\Override]
    public function up(): CreateTableStatement
    {
        return new CreateTableStatement('permissions')
            ->primary()
            ->varchar('name');
    }

    #[\Override]
    public function down(): DropTableStatement
    {
        return DropTableStatement::forModel(Permission::class);
    }
}
