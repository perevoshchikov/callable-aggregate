<?php

namespace Anper\CallableAggregate;

/**
 * Class CallableAggregate
 * @package Anper\CallableAggregate
 */
class CallableAggregate implements CallableAggregateInterface
{
    /**
     * @var callable[]
     */
    protected $collection = [];

    /**
     * @param iterable|callable[] $callbacks
     */
    public function __construct(iterable $callbacks = [])
    {
        $this->merge($callbacks);
    }

    /**
     * @inheritDoc
     */
    public function append(callable $callback)
    {
        $this->collection[] = $callback;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function prepend(callable $callback)
    {
        \array_unshift($this->collection, $callback);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function remove(callable $callback)
    {
        $index = \array_search($callback, $this->collection, true);

        if ($index !== false) {
            unset($this->collection[$index]);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function has(callable $callback): bool
    {
        return \in_array($callback, $this->collection, true);
    }

    /**
     * @param iterable $callbacks
     * @param bool $prepend
     *
     * @return $this
     */
    public function merge(iterable $callbacks, bool $prepend = false)
    {
        foreach ($callbacks as $callback) {
            if (\is_callable($callback)) {
                $prepend
                    ? $this->prepend($callback)
                    : $this->append($callback);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        $this->collection = [];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return \array_values($this->collection);
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->all());
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return \count($this->collection);
    }

    /**
     * @inheritDoc
     */
    public function __invoke(): void
    {
        $args = \func_get_args();

        foreach ($this->collection as $callback) {
            $callback(...$args);
        }
    }

    public function __clone()
    {
        foreach ($this->collection as $key => $callback) {
            if (\is_object($callback)) {
                $this->collection[$key] = clone $callback;
            }
        }
    }
}
