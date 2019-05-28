<?php

declare(strict_types=1);

namespace Th3Mouk\RethinkDB\Translator\Tests;

use ArrayObject;
use DateTime;
use PHPUnit\Framework\TestCase;
use r\Connection;
use r\Cursor;
use stdClass;
use Th3Mouk\RethinkDB\Translator\Translate;

class TranslateTest extends TestCase
{
    public function testCanNormalizeArrayObjectValues(): void
    {
        $values = [new ArrayObject(['foo' => 'bar']),
        ];

        $this->assertEquals(Translate::normalizeValues($values), [
            ['foo' => 'bar'],
        ]);
    }

    public function testCanNormalizeDateTimeValues(): void
    {
        $values = [new DateTime('2000-05-26T13:30:20')];

        $this->assertEquals(Translate::normalizeValues($values), ['2000-05-26T13:30:20+0200']);
    }

    public function testCanNormalizeNestedValues(): void
    {
        $values = [new ArrayObject([
            'foo' => new DateTime('2000-05-26T13:30:20'),
        ]),
        ];

        $this->assertEquals(Translate::normalizeValues($values), [
            ['foo' => '2000-05-26T13:30:20+0200'],
        ]);
    }

    public function testCanTransformArrayObjectToAssociativeArray(): void
    {
        $ao = new ArrayObject(['foo' => 'bar']);

        $this->assertEquals(Translate::arrayObjectToAssociativeArray($ao), ['foo' => 'bar']);
    }

    public function testCanTransformCursorToAssociativeArray(): void
    {
        $connection = $this->createMock(Connection::class);

        $data      = new stdClass();
        $data->foo = 'bar';
        $data->bar = 'foo';

        $cursor = new Cursor(
            $connection,
            [
                'r' => [$data],
                't' => 2,
            ],
            'token',
            [],
            []
        );

        $this->assertEquals(Translate::cursorToAssociativeArray($cursor), [
            [
                'foo' => 'bar',
                'bar' => 'foo',
            ],
        ]);
    }
}
