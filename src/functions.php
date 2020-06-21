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
    } elseif (\is_scalar($key) === false) {
        throw new \InvalidArgumentException('Key must be object or scalar, given ' . \gettype($key));
    }

    if (isset($collection[$key])) {
        return $collection[$key];
    }

    return $collection[$key] = new CallableAggregate();
}
