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
    private SiteKitResourceHierarchyLoader $hierarchyLoader;

    public function setUp(): void
    {
        $this->hierarchyLoader = $this->createLoader(
            SiteKitResourceHierarchyLoader::class
        );
    }

    private function createLoader(string $className)
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

        return new $className(
            $resourceLoader,
            'category'
        );
    }

    public function testGetResourceLoader(): void
    {
        $class = new \ReflectionClass(SiteKitResourceHierarchyLoader::class);
        $method = $class->getMethod('getResourceLoader');
        $this->assertNotNull(
            $method->invoke($this->hierarchyLoader),
            'getResourceLoader should return the resource-loader'
        );
    }

    public function testLoadPrimaryParentResourceWithoutParent(): void
    {

        $loader = $this->createLoader(
            TestSiteKitResourceHierarchyLoader::class
        );
        $this->expectException(InvalidResourceException::class);
        $loader->loadRoot('/a.php');
    }

    public function testLoadRoot(): void
    {
        $root = $this->hierarchyLoader->loadRoot('/c.php');

        $this->assertEquals(
            'a',
            $root->getId(),
            'unexpected root'
        );
    }

    public function testLoadParent(): void
    {
        $parent = $this->hierarchyLoader->loadParent('/c.php');

        $this->assertEquals(
            'b',
            $parent->getId(),
            'unexpected parent'
        );
    }

    public function testLoadChildren(): void
    {
        $children = $this->hierarchyLoader->loadChildren('/b.php');

        $childrenIdList = array_map(function ($child) {
            return $child->getId();
        }, $children);

        $this->assertEquals(
            ['c'],
            $childrenIdList,
            'unexpected children'
        );
    }

    public function testLoadChildrenWithInvalidData(): void
    {
        $this->expectException(InvalidResourceException::class);
        $children = $this->hierarchyLoader->loadChildren(
            '/childrenWithInvalidData.php'
        );
    }

    public function testLoadWithoutChildren(): void
    {
        $children = $this->hierarchyLoader->loadChildren('/c.php');

        $this->assertCount(
            0,
            $children,
            'children should be empty'
        );
    }

    public function testLoadPath(): void
    {
        $path = $this->hierarchyLoader->loadPath('/c.php');

        $pathIdList = array_map(function ($resource) {
            return $resource->getId();
        }, $path);

        $this->assertEquals(
            ['a', 'b', 'c'],
            $pathIdList,
            'unexpected path'
        );
    }

    public function testLoadParentWithoutParent(): void
    {
        $parent = $this->hierarchyLoader->loadParent('/a.php');
        $this->assertNull($parent, 'parent should be null');
    }

    public function testLoadRootResourcePrimaryParentWithoutUrl(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->hierarchyLoader->loadParent('/primaryParentWithoutUrl.php');
    }

    public function testLoadRootResourcePrimaryParentWithInvalidData(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->hierarchyLoader->loadParent('/primaryParentWithInvalidData.php');
    }

    public function testLoadRootResourcePrimaryParentWithNonStringUrl(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->hierarchyLoader->loadParent(
            '/primaryParentWithNonStringUrl.php'
        );
    }

    public function testLoadRootResourceFirstParentWithoutUrl(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->hierarchyLoader->loadParent('/firstParentWithoutUrl.php');
    }

    public function testLoadRootResourceFirstParentWithNonStringUrl(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->hierarchyLoader->loadParent('/firstParentWithNonStringUrl.php');
    }
}
