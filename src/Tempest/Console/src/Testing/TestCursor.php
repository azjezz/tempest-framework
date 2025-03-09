<?php

declare(strict_types=1);

namespace Tempest\Console\Testing;

use Tempest\Console\Cursor;
use Tempest\Console\Point;

final class TestCursor implements Cursor
{
    public function __construct(
        private int $x = 1,
        private int $y = 1,
    ) {
    }

    #[\Override]
    public function getPosition(): Point
    {
        return new Point($this->x, $this->y);
    }

    #[\Override]
    public function setPosition(Point $position): self
    {
        $this->x = $position->x;
        $this->y = $position->y;

        return $this;
    }

    #[\Override]
    public function moveUp(int $amount = 1): Cursor
    {
        $this->y = max(1, $this->y - 1);

        return $this;
    }

    #[\Override]
    public function moveDown(int $amount = 1): Cursor
    {
        $this->y += 1;

        return $this;
    }

    #[\Override]
    public function moveLeft(int $amount = 1): Cursor
    {
        $this->x = max(1, $this->x - 1);

        return $this;
    }

    #[\Override]
    public function moveRight(int $amount = 1): Cursor
    {
        $this->x += 1;

        return $this;
    }

    #[\Override]
    public function place(Point $position): Cursor
    {
        $this->x = max(1, $position->x);
        $this->y = max(1, $position->y);

        return $this;
    }

    #[\Override]
    public function placeToEnd(): Cursor
    {
        return $this;
    }

    #[\Override]
    public function clearLine(): Cursor
    {
        return $this;
    }

    #[\Override]
    public function clearAfter(): Cursor
    {
        return $this;
    }

    #[\Override]
    public function startOfLine(): Cursor
    {
        return $this;
    }

    #[\Override]
    public function hide(): Cursor
    {
        return $this;
    }

    #[\Override]
    public function show(): Cursor
    {
        return $this;
    }
}
