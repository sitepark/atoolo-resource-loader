<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test\Tree;

use Atoolo\Resource\Exception\InvalidResourceException;
use Atoolo\Resource\Exception\ResourceNotFoundException;
use Atoolo\Resource\Loader\SiteKitLoader;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLoader;
use Atoolo\Resource\Tree\SiteKitTreeLoader;
use Atoolo\Resource\TreeLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SiteKitTreeLoader::class)]
class SiteKitTreeLoaderTest extends TestCase
{
    private SiteKitTreeLoader $treeLoader;

    public function setUp(): void
    {
        $resourceBaseDir =
            realpath(__DIR__ . '/../resources/Tree/SiteKitTreeLoader');
        $resourceLoader = $this->createStub(
            ResourceLoader::class
        );
        $resourceLoader->method('load')
            ->willReturnCallback(static function ($location) use (
                $resourceBaseDir
            ) {
                return include $resourceBaseDir . $location;
            });

        $this->treeLoader = new SiteKitTreeLoader(
            $resourceLoader,
            'category'
        );
    }

    public function testGetResourceLoader(): void
    {
        $class = new \ReflectionClass(SiteKitTreeLoader::class);
        $method = $class->getMethod('getResourceLoader');
        $this->assertNotNull(
            $method->invoke($this->treeLoader),
            'getResourceLoader should return the resource-loader'
        );
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
