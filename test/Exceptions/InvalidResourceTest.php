<?php

namespace Atoolo\Resource\Test\Exceptions;

use Atoolo\Resource\Exceptions\InvalidResource;
use PHPUnit\Framework\TestCase;

class InvalidResourceTest extends TestCase
{
    public function testGetLocation(): void
    {
        $e = new InvalidResource('abc');
        $this->assertEquals(
            'abc',
            $e->getLocation(),
            'unexpected location'
        );
    }
}
