<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test\Loader;

use Atoolo\Resource\Loader\CachedResourceLoader;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CachedResourceLoader::class)]
class CachedResourceLoaderTest extends TestCase
{
    public function testLoad(): void
    {
        $resource = $this->createStub(Resource::class);
        $loader = $this->createMock(ResourceLoader::class);
        $loader->expects($this->once())
            ->method('load')
            ->with('test')
            ->willReturn($resource);
        $cachedLoader = new CachedResourceLoader($loader);
        $cachedLoader->load('test'); // cache warmup

        $this->assertEquals(
            $resource,
            $cachedLoader->load('test'),
            'Resource should be loaded from cache'
        );
    }

    public function testExistsUncached(): void
    {
        $loader = $this->createMock(ResourceLoader::class);
        $loader->expects($this->once())
            ->method('exists')
            ->with('test')
            ->willReturn(true);
        $cachedLoader = new CachedResourceLoader($loader);

        $this->assertTrue(
            $cachedLoader->exists('test'),
            'Resource should be test from cache'
        );
    }

    public function testExistsCached(): void
    {

        $resource = $this->createStub(Resource::class);
        $loader = $this->createMock(ResourceLoader::class);
        $loader->expects($this->once())
            ->method('load')
            ->with('test')
            ->willReturn($resource);
        $loader->expects($this->exactly(0))
            ->method('exists')
            ->with('test')
            ->willReturn(true);

        $cachedLoader = new CachedResourceLoader($loader);
        $cachedLoader->load('test'); // cache warmup

        $this->assertTrue(
            $cachedLoader->exists('test'),
            'Resource should be test from cache'
        );
    }
}
