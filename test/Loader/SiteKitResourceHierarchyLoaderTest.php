<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test\Loader;

use Atoolo\Resource\Exception\InvalidResourceException;
use Atoolo\Resource\Loader\SiteKitResourceHierarchyLoader;
use Atoolo\Resource\ResourceLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SiteKitResourceHierarchyLoader::class)]
class SiteKitResourceHierarchyLoaderTest extends TestCase
{
    private SiteKitResourceHierarchyLoader $treeLoader;

    public function setUp(): void
    {
        $resourceBaseDir = realpath(
            __DIR__ . '/../resources/' .
                'Loader/SiteKitResourceHierarchyLoader'
        );
        $resourceLoader = $this->createStub(
            ResourceLoader::class
        );
        $resourceLoader->method('load')
            ->willReturnCallback(static function ($location) use (
                $resourceBaseDir
            ) {
                return include $resourceBaseDir . $location;
            });

        $this->treeLoader = new SiteKitResourceHierarchyLoader(
            $resourceLoader,
            'category'
        );
    }

    public function testGetResourceLoader(): void
    {
        $class = new \ReflectionClass(SiteKitResourceHierarchyLoader::class);
        $method = $class->getMethod('getResourceLoader');
        $this->assertNotNull(
            $method->invoke($this->treeLoader),
            'getResourceLoader should return the resource-loader'
        );
    }

    public function testLoadPrimaryParentResourceWithoutParent(): void
    {
        $location = realpath(
            __DIR__ . '/../resources/' .
            'Loader/SiteKitResourceHierarchyLoader/a.php'
        );
        $resource = include $location;

        $class = new \ReflectionClass(SiteKitResourceHierarchyLoader::class);
        $method = $class->getMethod('loadPrimaryParentResource');
        $this->expectException(InvalidResourceException::class);
        $method->invoke($this->treeLoader, $resource);
    }

    public function testLoadRootResource(): void
    {
        $root = $this->treeLoader->loadRootResource('/c.php');

        $this->assertEquals(
            'a',
            $root->getId(),
            'unexpected root'
        );
    }
}
