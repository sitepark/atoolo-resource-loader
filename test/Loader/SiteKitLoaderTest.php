<?php

namespace Atoolo\Resource\Test\Loader;

use Atoolo\Resource\Exceptions\InvalidResource;
use Atoolo\Resource\Exceptions\ResourceNotFound;
use Atoolo\Resource\Loader\SiteKitLoader;
use Atoolo\Resource\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SiteKitLoader::class)]
class SiteKitLoaderTest extends TestCase
{
    private SiteKitLoader $loader;

    protected function setUp(): void
    {
        $base = realpath(__DIR__ . '/../resources/Loader/SiteKitLoader');
        $this->loader = new SiteKitLoader($base);
    }

    public function testLoadValidResource(): void
    {
        $resource = $this->loader->load('validResource.php');
        $this->assertEquals(
            '1118',
            $resource->getId(),
            'unexpected id'
        );
    }

    public function testLoadMissingLocation(): void
    {
        $this->expectException(ResourceNotFound::class);
        $this->loader->load('notfound.php');
    }

    public function testLoadResourceWithCompileError(): void
    {
        $this->expectException(InvalidResource::class);
        $this->loader->load('compileError.php');
    }

    public function testLoadWithMissingId(): void
    {
        $this->expectException(InvalidResource::class);
        $this->loader->load('missingIdResource.php');
    }

    public function testLoadWithMissingName(): void
    {
        $this->expectException(InvalidResource::class);
        $this->loader->load('missingNameResource.php');
    }

    public function testLoadWithMissingObjectType(): void
    {
        $this->expectException(InvalidResource::class);
        $this->loader->load('missingObjectTypeResource.php');
    }
}
