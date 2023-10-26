<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test;

use Atoolo\Resource\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Resource::class)]
class ResourceTest extends TestCase
{
    public function testConstructor(): void
    {
        $resource = new Resource(
            'path',
            '123',
            'Content-Page',
            'content',
            []
        );
        $this->assertEquals(
            'path',
            $resource->getLocation(),
            'unexpected location'
        );
        $this->assertEquals(
            '123',
            $resource->getId(),
            'unexpected id'
        );
        $this->assertEquals(
            'Content-Page',
            $resource->getName(),
            'unexpected name'
        );
        $this->assertEquals(
            'content',
            $resource->getObjectType(),
            'unexpected content'
        );
    }

    public function testGetData(): void
    {
        $resource = new Resource(
            '',
            '',
            '',
            '',
            ['field' => 'value']
        );
        $this->assertEquals(
            'value',
            $resource->getData('field'),
            'unexpected data'
        );
    }

    public function testGetMissingData(): void
    {
        $resource = new Resource(
            '',
            '',
            '',
            '',
            []
        );
        $this->assertNull(
            $resource->getData('field'),
            'null should be returned'
        );
    }

    public function testGetNestedData(): void
    {
        $resource = new Resource(
            '',
            '',
            '',
            '',
            data: ['field' => [
                'values' => [1, 2, 3]
            ]]
        );
        $this->assertEquals(
            [1, 2, 3],
            $resource->getData('field.values'),
            'unexpected nested data'
        );
    }
}
