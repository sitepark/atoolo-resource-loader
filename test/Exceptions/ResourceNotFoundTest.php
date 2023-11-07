<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test\Exceptions;

use Atoolo\Resource\Exception\InvalidResourceException;
use Atoolo\Resource\Exception\ResourceNotFoundException;
use PHPUnit\Framework\TestCase;

class ResourceNotFoundTest extends TestCase
{
    public function testGetLocation(): void
    {
        $e = new ResourceNotFoundException('abc');
        $this->assertEquals(
            'abc',
            $e->getLocation(),
            'unexpected location'
        );
    }
}
