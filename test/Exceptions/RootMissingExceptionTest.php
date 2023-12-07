<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test\Exceptions;

use Atoolo\Resource\Exception\InvalidResourceException;
use Atoolo\Resource\Exception\RootMissingException;
use PHPUnit\Framework\TestCase;

class RootMissingExceptionTest extends TestCase
{
    public function testGetLocation(): void
    {
        $e = new RootMissingException('abc');
        $this->assertEquals(
            'abc',
            $e->getLocation(),
            'unexpected location'
        );
    }
}
