<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test;

use Atoolo\Resource\DataBag;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLanguage;
use Atoolo\Resource\ResourceLocation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Resource::class)]
class ResourceTest extends TestCase
{
    public function testToLocation(): void
    {
        $resource = new Resource(
            'path',
            '',
            '',
            '',
            \Atoolo\Resource\ResourceLanguage::of('en'),
            new \Atoolo\Resource\DataBag([])
        );
        $this->assertEquals(
            ResourceLocation::of(
                'path',
                \Atoolo\Resource\ResourceLanguage::of('en')
            ),
            $resource->toLocation(),
            'unexpected data value'
        );
    }
}
