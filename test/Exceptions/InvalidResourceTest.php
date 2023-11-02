<?php

namespace Atoolo\Resource\Test\Exceptions;

use Atoolo\Resource\Exception\InvalidResourceException;
use PHPUnit\Framework\TestCase;

class InvalidResourceTest extends TestCase
{
    public function testGetLocation(): void
    {
        $e = new InvalidResourceException('abc');
        $this->assertEquals(
            'abc',
            $e->getLocation(),
            'unexpected location'
        );
    }
}
