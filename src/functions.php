<?php

namespace Anper\CallableAggregate;

/**
 * @param object|string|int $key
 *
 * @return CallableAggregateInterface
 * @throws \InvalidArgumentException
 */
function aggregate($key): CallableAggregateInterface
{
    static $collection = [];

    if (\is_object($key)) {
        $key = \spl_object_hash($key);
    } elseif (!\is_scalar($key)) {
        throw new \InvalidArgumentException('Key must be object or scalar, given ' . \gettype($key));
    }

    if (isset($collection[$key])) {
        return $collection[$key];
    }

    return $collection[$key] = new CallableAggregate();
}

/**
 * @param object|string|int $key
 * @param callable $callback
 * @param bool $prepend
 *
 * @return bool
 */
function register_callback($key, callable $callback, bool $prepend = false): bool
{
    $collection = aggregate($key);

    $prepend
        ? $collection->prepend($callback)
        : $collection->append($callback);

    return true;
}

/**
 * @param object|string|int $key
 * @param callable $callback
 *
 * @return bool
 */
function unregister_callback($key, callable $callback): bool
{
    $collection = aggregate($key);

    if ($has = $collection->has($callback)) {
        $collection->remove($callback);
    }

    return $has;
}

/**
 * @param object|string|int $key
 *
 * @return array|callable[]
 */
function get_callbacks($key): array
{
    return aggregate($key)->all();
}

/**
 * @param object|string|int $key
 *
 * @return int
 */
function clear_callbacks($key): int
{
    $collection = aggregate($key);

    if ($count = $collection->count()) {
        $collection->clear();
    }

    return $count;
}
