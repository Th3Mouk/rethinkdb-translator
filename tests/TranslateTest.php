<?php

declare(strict_types=1);

namespace Th3Mouk\RethinkDB\Translator\Tests;

use PHPUnit\Framework\TestCase;
use r\Connection;
use r\Cursor;
use Th3Mouk\RethinkDB\Translator\Translate;

class TranslateTest extends TestCase
{
    public function testCanNormalizeArrayObjectValues(): void
    {
        $values = [new \ArrayObject(['foo' => 'bar']),
        ];

        $this->assertEquals(Translate::normalizeValues($values), [
            ['foo' => 'bar'],
        ]);
    }

    public function testCanNormalizeDateTimeValues(): void
    {
        $values = [new \DateTime('2000-05-26T13:30:20+0000')];

        $this->assertEquals(Translate::normalizeValues($values), ['2000-05-26T13:30:20+0000']);
    }

    public function testCanNormalizeNestedValues(): void
    {
        $values = [
            new \ArrayObject([
                'foo' => new \DateTime('2000-05-26T13:30:20+0000'),
            ]),
        ];

        $this->assertEquals(Translate::normalizeValues($values), [
            ['foo' => '2000-05-26T13:30:20+0000'],
        ]);
    }

    public function testCanOverrideDateTimeToStringOption(): void
    {
        $values = [new \ArrayObject([
            'foo' => new \DateTime('2000-05-26T13:30:20+0000'),
        ]),
        ];

        $normalized = Translate::normalizeValues($values, ['dateTimeToString' => false]);

        $this->assertInstanceOf(\DateTime::class, $normalized[0]['foo']);
        $this->assertEquals($normalized[0]['foo']->format(DATE_ISO8601), '2000-05-26T13:30:20+0000');
    }

    public function testCanNormalizeNestedArrayObjectInArray(): void
    {
        $values = [
            [
                new \ArrayObject(['foo' => 'bar']),
            ],
        ];

        $this->assertEquals(Translate::normalizeValues($values), [
            [
                ['foo' => 'bar'],
            ],
        ]);
    }

    public function testCanTransformArrayObjectToAssociativeArray(): void
    {
        $ao = new \ArrayObject(['foo' => 'bar']);

        $this->assertEquals(Translate::arrayObjectToAssociativeArray($ao), ['foo' => 'bar']);
    }

    /**
     * @throws \ReflectionException
     */
    public function testCanTransformCursorToAssociativeArray(): void
    {
        $connection = $this->createMock(Connection::class);

        $data      = new \stdClass();
        $data->foo = 'bar';
        $data->bar = 'foo';

        /** @var Connection $connection */
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

    public function testCanTransformArrayOfArrayObjectToAssociativeArray(): void
    {
        $mockResult = [];

        $mockResult[] = new \ArrayObject([
            'domains' => ['cine.fr'],
            'id' => '46ba3de0-3210-4f7b-aac9-c5176daa0cca',
            'status_changed_at' => new \DateTime('2000-05-26T13:30:20+0000'),
        ]);

        $mockResult[] = new \ArrayObject([
            'domains' => ['nyxinteractive.eu', 'irond.info'],
            'id' => '4640ebcb-48f0-4948-8d5b-93cf08d4249c',
            'status_changed_at' => new \DateTime('2000-05-27T13:30:20+0000'),
        ]);

        $this->assertEquals(Translate::arrayOfArrayObjectToAssociativeArray($mockResult), [
            [
                'domains' => ['cine.fr'],
                'id' => '46ba3de0-3210-4f7b-aac9-c5176daa0cca',
                'status_changed_at' => '2000-05-26T13:30:20+0000',
            ],
            [
                'domains' => ['nyxinteractive.eu', 'irond.info'],
                'id' => '4640ebcb-48f0-4948-8d5b-93cf08d4249c',
                'status_changed_at' => '2000-05-27T13:30:20+0000',
            ],
        ]);
    }
}
