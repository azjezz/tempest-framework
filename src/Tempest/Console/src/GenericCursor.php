<?php

declare(strict_types=1);

namespace Tempest\Console;

final class GenericCursor implements Cursor
{
    public Point $position;

    public function __construct()
    {
        $this->position = new Point(0, 0);
    }

    #[\Override]
    public function getPosition(): Point
    {
        return $this->position;
    }

    #[\Override]
    public function setPosition(Point $position): Cursor
    {
        $this->position = $position;

        return $this;
    }

    #[\Override]
    public function moveUp(int $amount = 1): Cursor
    {
        $this->position->y -= $amount;

        return $this;
    }

    #[\Override]
    public function moveDown(int $amount = 1): Cursor
    {
        $this->position->y += $amount;

        return $this;
    }

    #[\Override]
    public function moveLeft(int $amount = 1): Cursor
    {
        $this->position->x -= $amount;

        return $this;
    }

    #[\Override]
    public function moveRight(int $amount = 1): Cursor
    {
        $this->position->x += $amount;

        return $this;
    }

    #[\Override]
    public function place(Point $position): Cursor
    {
        return $this->setPosition($position);
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
