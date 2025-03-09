<?php

declare(strict_types=1);

namespace Tempest\Database\Connection;

use PDO;
use PDOStatement;
use Tempest\Database\Config\DatabaseConfig;
use Tempest\Database\Exceptions\ConnectionClosed;

final class PDOConnection implements Connection
{
    private ?PDO $pdo = null;

    public function __construct(private readonly DatabaseConfig $config)
    {
    }

    #[\Override]
    public function beginTransaction(): bool
    {
        if ($this->pdo === null) {
            throw new ConnectionClosed();
        }

        return $this->pdo->beginTransaction();
    }

    #[\Override]
    public function commit(): bool
    {
        if ($this->pdo === null) {
            throw new ConnectionClosed();
        }

        return $this->pdo->commit();
    }

    #[\Override]
    public function rollback(): bool
    {
        if ($this->pdo === null) {
            throw new ConnectionClosed();
        }

        return $this->pdo->rollBack();
    }

    #[\Override]
    public function lastInsertId(): false|string
    {
        if ($this->pdo === null) {
            throw new ConnectionClosed();
        }

        return $this->pdo->lastInsertId();
    }

    #[\Override]
    public function prepare(string $sql): false|PDOStatement
    {
        if ($this->pdo === null) {
            throw new ConnectionClosed();
        }

        return $this->pdo->prepare($sql);
    }

    #[\Override]
    public function close(): void
    {
        $this->pdo = null;
    }

    #[\Override]
    public function connect(): void
    {
        if ($this->pdo !== null) {
            return;
        }

        $this->pdo = new PDO(
            $this->config->dsn,
            $this->config->username,
            $this->config->password,
        );
    }
}
