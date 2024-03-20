<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test;

use Atoolo\Resource\DataBag;
use Atoolo\Resource\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Resource::class)]
class ResourceTest extends TestCase
{
    public function testGetLocation(): void
    {
        $resource = new Resource(
            'path',
            '',
            '',
            '',
            '',
            []
        );
        $this->assertEquals(
            'path',
            $resource->getLocation(),
            'unexpected location'
        );
    }

    public function testGetId(): void
    {
        $resource = new Resource(
            '',
            '123',
            '',
            '',
            '',
            []
        );
        $this->assertEquals(
            '123',
            $resource->getId(),
            'unexpected id'
        );
    }

    public function testGetName(): void
    {
        $resource = new Resource(
            '',
            '',
            'Content-Page',
            '',
            '',
            []
        );
        $this->assertEquals(
            'Content-Page',
            $resource->getName(),
            'unexpected name'
        );
    }

    public function testGetObjectType(): void
    {
        $resource = new Resource(
            '',
            '',
            '',
            'content',
            '',
            []
        );
        $this->assertEquals(
            'content',
            $resource->getObjectType(),
            'unexpected content'
        );
    }

    public function testGetLang(): void
    {
        $resource = new Resource(
            '',
            '',
            '',
            'content',
            'en',
            []
        );
        $this->assertEquals(
            'en',
            $resource->getLang(),
            'unexpected lang'
        );
    }

    public function testGetData(): void
    {
        $resource = new Resource(
            '',
            '',
            '',
            '',
            '',
            ['field' => 'value']
        );
        $this->assertEquals(
            'value',
            $resource->getData()->getString('field'),
            'unexpected data value'
        );
    }
}
