<?php

namespace Atoolo\Resource\Test\Exceptions;

use Atoolo\Resource\Exceptions\InvalidResource;
use Atoolo\Resource\Exceptions\ResourceNotFound;
use PHPUnit\Framework\TestCase;

class ResourceNotFoundTest extends TestCase
{
    public function testGetLocation(): void
    {
        $e = new ResourceNotFound('abc');
        $this->assertEquals(
            'abc',
            $e->getLocation(),
            'unexpected location'
        );
    }
}
