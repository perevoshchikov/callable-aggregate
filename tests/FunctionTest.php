<?php

namespace Anper\CallableAggregate\Tests;

use PHPUnit\Framework\TestCase;

use function Anper\CallableAggregate\aggregate;
use function Anper\CallableAggregate\clear_callbacks;
use function Anper\CallableAggregate\get_callbacks;
use function Anper\CallableAggregate\register_callback;
use function Anper\CallableAggregate\unregister_callback;

/**
 * Class FunctionTest
 * @package Anper\CallableAggregate\Tests
 */
class FunctionTest extends TestCase
{
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
        $this->callbacks = [];

        foreach ($this->keyProvider() as $item) {
            clear_callbacks($item[0]);
        }

        parent::tearDown();
    }

    /**
     * @return array
     */
    public function keyProvider(): array
    {
        return [
            [$this],
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

    /**
     * @dataProvider keyProvider
     * @param mixed $key
     */
    public function testRegisterAppend($key): void
    {
        register_callback($key, $this->callbacks[0]);
        register_callback($key, $this->callbacks[1]);

        $this->assertSame([
            $this->callbacks[0],
            $this->callbacks[1]
        ], get_callbacks($key));
    }

    /**
     * @dataProvider keyProvider
     * @param mixed $key
     */
    public function testRegisterPrepend($key): void
    {
        register_callback($key, $this->callbacks[0], true);
        register_callback($key, $this->callbacks[1], true);

        $this->assertSame([
            $this->callbacks[1],
            $this->callbacks[0]
        ], get_callbacks($key));
    }

    /**
     * @dataProvider keyProvider
     * @param mixed $key
     */
    public function testUnregister($key): void
    {
        register_callback($key, $this->callbacks[0]);
        register_callback($key, $this->callbacks[1]);

        $this->assertSame([
            $this->callbacks[0],
            $this->callbacks[1]
        ], get_callbacks($key));

        $result1 = unregister_callback($key, $this->callbacks[0]);
        $result2 = unregister_callback($key, function ($a) {
            //
        });

        $this->assertTrue($result1);
        $this->assertFalse($result2);
        $this->assertSame([
            $this->callbacks[1]
        ], get_callbacks($key));
    }

    /**
     * @dataProvider keyProvider
     * @param mixed $key
     */
    public function testClear($key): void
    {
        register_callback($key, $this->callbacks[0]);
        register_callback($key, $this->callbacks[1]);

        $this->assertSame([
            $this->callbacks[0],
            $this->callbacks[1]
        ], get_callbacks($key));

        $count = clear_callbacks($key);

        $this->assertEquals(2, $count);
        $this->assertEquals([], get_callbacks($key));
    }
}
