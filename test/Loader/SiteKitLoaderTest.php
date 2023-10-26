<?php

namespace Atoolo\Resource\Test\Loader;

use Atoolo\Resource\Exceptions\InvalidData;
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

    public function testLoad(): void
    {
        $resource = $this->loader->load('validResource.php');
        $this->assertEquals(
            '1118',
            $resource->getId(),
            'unexpected id'
        );
    }

    public function testLoadWithMissingId(): void
    {
        $this->expectException(InvalidData::class);
        $this->loader->load('missingIdResource.php');
    }

    public function testLoadWithMissingName(): void
    {
        $this->expectException(InvalidData::class);
        $this->loader->load('missingNameResource.php');
    }

    public function testLoadWithMissingObjectType(): void
    {
        $this->expectException(InvalidData::class);
        $this->loader->load('missingObjectTypeResource.php');
    }
}
