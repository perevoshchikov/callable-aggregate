<?php

namespace Anper\CallableAggregate\Tests;

use PHPUnit\Framework\TestCase;

use function Anper\CallableAggregate\aggregate;

/**
 * Class AggregateTest
 * @package Anper\CallableAggregate\Tests
 */
class AggregateTest extends TestCase
{
    /**
     * @return array
     */
    public function keyProvider(): array
    {
        return [
            [new self()],
            [true],
            [false],
            [1.1],
            [1],
            ['string']
        ];
    }

    /**
     * @return array
     */
    public function invalidKeyProvider(): array
    {
        return [
            [[]],
        ];
    }

    /**
     * @dataProvider keyProvider
     * @param mixed $key
     */
    public function testAggregate($key): void
    {
        $collection1 = aggregate($key);
        $collection2 = aggregate($key);

        $this->assertSame($collection1, $collection2);
    }

    /**
     * @dataProvider invalidKeyProvider
     * @param mixed $key
     */
    public function testInvalidKey($key): void
    {
        $this->expectException(\InvalidArgumentException::class);

        aggregate($key);
    }
}
