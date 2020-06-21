<?php

namespace Anper\CallableAggregate\Tests;

use Anper\CallableAggregate\CallableAggregate;
use PHPUnit\Framework\TestCase;

/**
 * Class CallableAggregateTest
 * @package Anper\CallableAggregate\Tests
 */
class CallableAggregateTest extends TestCase
{
    /**
     * @var CallableAggregate
     */
    protected $aggregate;

    /**
     * @var array
     */
    protected $callbacks = [];

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->aggregate = new CallableAggregate();
        $this->callbacks[] = static function () {
            //
        };
        $this->callbacks[] = static function () {
            //
        };
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        $this->aggregate = null;
        $this->callbacks = [];
        parent::tearDown();
    }

    public function testAppend(): void
    {
        $this->aggregate->append($this->callbacks[0]);
        $this->aggregate->append($this->callbacks[1]);

        $this->assertSame([
            $this->callbacks[0],
            $this->callbacks[1]
        ], $this->aggregate->all());
    }

    public function testPrepend(): void
    {
        $this->aggregate->prepend($this->callbacks[0]);
        $this->aggregate->prepend($this->callbacks[1]);

        $this->assertSame([
            $this->callbacks[1],
            $this->callbacks[0]
        ], $this->aggregate->all());
    }

    public function testHas(): void
    {
        $this->aggregate->append($this->callbacks[0]);

        $this->assertTrue($this->aggregate->has($this->callbacks[0]));
        $this->assertFalse($this->aggregate->has($this->callbacks[1]));
    }

    public function testRemove(): void
    {
        $this->aggregate->append($this->callbacks[0]);
        $this->aggregate->append($this->callbacks[1]);

        $this->assertTrue($this->aggregate->has($this->callbacks[0]));
        $this->assertTrue($this->aggregate->has($this->callbacks[1]));

        $this->aggregate->remove($this->callbacks[0]);

        $this->assertFalse($this->aggregate->has($this->callbacks[0]));
        $this->assertTrue($this->aggregate->has($this->callbacks[1]));
    }

    public function testCount(): void
    {
        $this->aggregate->append($this->callbacks[0]);

        $this->assertSame(1, $this->aggregate->count());

        $this->aggregate->append($this->callbacks[1]);

        $this->assertSame(2, $this->aggregate->count());
    }

    public function testClear(): void
    {
        $this->aggregate->append($this->callbacks[0]);

        $this->assertSame(1, $this->aggregate->count());

        $this->aggregate->clear();

        $this->assertSame(0, $this->aggregate->count());
    }

    public function testAppendMerge(): void
    {
        $this->aggregate->append($this->callbacks[0]);
        $this->aggregate->merge([$this->callbacks[1]]);

        $this->assertSame([
            $this->callbacks[0],
            $this->callbacks[1]
        ], $this->aggregate->all());
    }

    public function testPrependMerge(): void
    {
        $this->aggregate->append($this->callbacks[0]);
        $this->aggregate->merge([$this->callbacks[1]], true);

        $this->assertSame([
            $this->callbacks[1],
            $this->callbacks[0]
        ], $this->aggregate->all());
    }

    public function testConstructor(): void
    {
        $aggregate = new CallableAggregate($this->callbacks);

        $this->assertSame($this->callbacks, $aggregate->all());
    }

    public function testInvoke(): void
    {
        $str = 'string';
        $int = 12345678;

        $callback1 = $this->createMock(CallableMock::class);
        $callback1->expects($this->once())
            ->method('__invoke')
            ->with($str, $int);

        $callback2 = $this->createMock(CallableMock::class);
        $callback2->expects($this->once())
            ->method('__invoke')
            ->with($str, $int);

        $this->aggregate->append($callback1);
        $this->aggregate->append($callback2);

        $this->aggregate->__invoke($str, $int);
    }

    public function testGetIterator(): void
    {
        $this->aggregate->append($this->callbacks[0]);
        $this->aggregate->merge([$this->callbacks[1]]);

        $iterator = $this->aggregate->getIterator();
        $callbacks = \iterator_to_array($iterator);

        $this->assertSame($callbacks, $this->aggregate->all());
    }

    public function testClone()
    {
        $this->aggregate->append([$this, __FUNCTION__]);
        $callback2 = $this->createMock(CallableMock::class);

        $this->aggregate->append($callback2);

        $clone = clone $this->aggregate;

        $current = $this->aggregate->all();
        $cloned = $clone->all();

        $this->assertSame($current[0], $cloned[0]);
        $this->assertNotSame($current[1], $cloned[1]);
        $this->assertEquals($current[1], $cloned[1]);
    }
}
