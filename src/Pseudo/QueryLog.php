<?php

namespace Pseudo;

use InvalidArgumentException;

class QueryLog implements \IteratorAggregate, \ArrayAccess, \Countable
{
    private $queries = [];

    public function count(): int
    {
        return count($this->queries);
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->queries);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->queries[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        if (!$this->offsetExists($offset)) {
            throw new InvalidArgumentException("Offset $offset does not exist");
        }

        return $this->queries[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->queries[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->queries[$offset]);
    }

    public function addQuery($sql): void
    {
        $this->queries[] = new ParsedQuery($sql);
    }

    public function getQueries(): array
    {
        return $this->queries;
    }
}
