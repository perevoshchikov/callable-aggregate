<?php

namespace Anper\CallableAggregate;

/**
 * @param object|string|int $key
 * @param bool|null $created
 *
 * @return CallableAggregateInterface
 */
function aggregator($key, bool &$created = null): CallableAggregateInterface
{
    static $collection = [];

    if (\is_object($key)) {
        $key = \spl_object_hash($key);
    } elseif (!\is_scalar($key)) {
        throw new \InvalidArgumentException('Key must be object or scalar, given ' . \gettype($key));
    }

    $created = false;

    if (isset($collection[$key])) {
        return $collection[$key];
    }

    $created = true;

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
    $collection = aggregator($key);

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
    $collection = aggregator($key);

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
    return aggregator($key)->all();
}

/**
 * @param object|string|int $key
 *
 * @return int
 */
function clear_callbacks($key): int
{
    $collection = aggregator($key);

    if ($count = $collection->count()) {
        $collection->clear();
    }

    return $count;
}
