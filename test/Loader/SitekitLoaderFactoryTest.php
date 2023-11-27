<?php

namespace Atoolo\Resource\Test\Loader;

use Atoolo\Resource\Loader\SiteKitLoaderFactory;
use PHPUnit\Framework\TestCase;

class SitekitLoaderFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $factory = new SiteKitLoaderFactory();
        $loader = $factory->create('mybasepath');
        $this->assertNotNull($loader, 'loader should not be null');
    }
}
