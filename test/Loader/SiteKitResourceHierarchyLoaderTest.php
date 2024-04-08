<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test\Loader;

use Atoolo\Resource\Exception\InvalidResourceException;
use Atoolo\Resource\Loader\SiteKitResourceHierarchyLoader;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
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

    /**
     * @throws Exception
     */
    public function testLoad(): void
    {
        $resourceLoader = $this->createMock(ResourceLoader::class);
        $hierarchyLoader = new SiteKitResourceHierarchyLoader(
            $resourceLoader,
            'category'
        );

        $resourceLoader->expects($this->once())
            ->method('load');

        $hierarchyLoader->load('/a.php');
    }

    /**
     * @throws Exception
     */
    public function testExists(): void
    {
        $resourceLoader = $this->createMock(ResourceLoader::class);
        $hierarchyLoader = new SiteKitResourceHierarchyLoader(
            $resourceLoader,
            'category'
        );

        $resourceLoader->expects($this->once())
            ->method('exists');

        $hierarchyLoader->exists('/a.php');
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

    public function testLoadPrimaryParent(): void
    {
        $parent = $this->hierarchyLoader->loadPrimaryParent('/c.php');

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

    public function testLoadPrimaryPath(): void
    {
        $path = $this->hierarchyLoader->loadPrimaryPath('/c.php');

        $pathIdList = array_map(function ($resource) {
            return $resource->getId();
        }, $path);

        $this->assertEquals(
            ['a', 'b', 'c'],
            $pathIdList,
            'unexpected path'
        );
    }

    public function testLoadPrimaryParentWithoutParent(): void
    {
        $parent = $this->hierarchyLoader->loadPrimaryParent('/a.php');
        $this->assertNull($parent, 'parent should be null');
    }

    public function testLoadRootResourcePrimaryParentWithoutUrl(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->hierarchyLoader->loadPrimaryParent(
            '/primaryParentWithoutUrl.php'
        );
    }

    public function testLoadRootResourcePrimaryParentWithInvalidData(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->hierarchyLoader->loadPrimaryParent(
            '/primaryParentWithInvalidData.php'
        );
    }

    public function testLoadRootResourcePrimaryParentWithNonStringUrl(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->hierarchyLoader->loadPrimaryParent(
            '/primaryParentWithNonStringUrl.php'
        );
    }

    public function testLoadRootResourceFirstParentWithoutUrl(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->hierarchyLoader->loadPrimaryParent(
            '/firstParentWithoutUrl.php'
        );
    }

    public function testLoadRootResourceFirstParentWithNonStringUrl(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->hierarchyLoader->loadPrimaryParent(
            '/firstParentWithNonStringUrl.php'
        );
    }

    public function testLoadParent(): void
    {
        $parent = $this->hierarchyLoader->loadParent(
            '/b.php',
            'a'
        );
        $this->assertEquals(
            'a',
            $parent->getId(),
            'unexpected parent'
        );
    }

    public function testLoadParentIdNotFound(): void
    {
        $parent = $this->hierarchyLoader->loadParent(
            '/b.php',
            'x'
        );
        $this->assertNull(
            $parent,
            'parent should be null'
        );
    }

    public function testLoadParentOfRoot(): void
    {
        $parent = $this->hierarchyLoader->loadParent(
            '/a.php',
            'x'
        );
        $this->assertNull(
            $parent,
            'parent should be null'
        );
    }

    public function testGetParentLocationWithoutParents(): void
    {
        $resource = new Resource(
            '',
            '',
            '',
            '',
            []
        );
        $parent = $this->hierarchyLoader->getParentLocation(
            $resource,
            'x'
        );
        $this->assertNull(
            $parent,
            'parent should be null'
        );
    }

    public function testGetParentLocationWithInvalidData(): void
    {
        $resource = new Resource(
            '',
            '',
            '',
            '',
            [
                'base' => [
                    'trees' => [
                        'category' => [
                            'parents' => [
                                'a' => 'invalid'
                            ]
                        ]
                    ]
                ]
            ]
        );

        $this->expectException(InvalidResourceException::class);
        $this->hierarchyLoader->getParentLocation(
            $resource,
            'x'
        );
    }

    public function testGetParentLocationWithParentIdNotFound(): void
    {
        $resource = new Resource(
            '',
            '',
            '',
            '',
            [
                'base' => [
                    'trees' => [
                        'category' => [
                            'parents' => [
                                'a' => [
                                    'id' => 'a',
                                    'url' => '/a.php'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );

        $parent = $this->hierarchyLoader->getParentLocation(
            $resource,
            'x'
        );
        $this->assertNull(
            $parent,
            'parent should be null'
        );
    }
}
