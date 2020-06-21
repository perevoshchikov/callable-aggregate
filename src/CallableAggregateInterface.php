<?php

namespace Anper\CallableAggregate;

/**
 * Interface CallableAggregateInterface
 * @package Anper\CallableAggregate
 *
 * @extends \IteratorAggregate<int, callable>
 */
interface CallableAggregateInterface extends \IteratorAggregate, \Countable
{
    /**
     * @param callable $callback
     *
     * @return $this
     */
    public function append(callable $callback);

    /**
     * @param callable $callback
     *
     * @return $this
     */
    public function prepend(callable $callback);

    /**
     * @param callable $callback
     *
     * @return $this
     */
    public function remove(callable $callback);

    /**
     * @param callable $callback
     *
     * @return bool
     */
    public function has(callable $callback): bool;

    /**
     * @param iterable|callable[] $callbacks
     *
     * @return $this
     */
    public function merge(iterable $callbacks);

    /**
     * @return $this
     */
    public function clear();

    /**
     * @return array|callable[]
     */
    public function all(): array;

    /**
     * @return \Traversable|callable[]
     */
    public function getIterator();

    public function __invoke(): void;
}
