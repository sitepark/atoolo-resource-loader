<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test\Tree;

use Atoolo\Resource\Exception\RootMissingException;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLoader;
use Atoolo\Resource\Tree\SiteKitNavigationTreeLoader;
use Atoolo\Resource\Tree\SiteKitTreeLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SiteKitNavigationTreeLoader::class)]
class SiteKitNavigationTreeLoaderTest extends TestCase
{
    public function testLoadRootResourceWithHomeFlag(): void
    {
        $treeLoader = $this->createTreeLoader(
            realpath(
                __DIR__ .
                '/../resources/Tree/SiteKitNavigationTreeLoader/withHomeFlag'
            )
        );

        $root = $treeLoader->loadRootResource('/c.php');

        $this->assertEquals(
            'a',
            $root->getId(),
            'unexpected root'
        );
    }

    public function testLoadRootResourceWithDefaultRoot(): void
    {
        $treeLoader = $this->createTreeLoader(
            realpath(
                __DIR__ .
                '/../resources/Tree/SiteKitNavigationTreeLoader/withDefaultRoot'
            )
        );

        $root = $treeLoader->loadRootResource('/dir/c.php');

        $this->assertEquals(
            'root',
            $root->getId(),
            'unexpected root'
        );
    }

    public function testLoadRootResourceWithoutRoot(): void
    {
        $treeLoader = $this->createTreeLoader(
            realpath(
                __DIR__ .
                '/../resources/Tree/SiteKitNavigationTreeLoader/withoutRoot'
            )
        );

        $this->expectException(RootMissingException::class);
        $treeLoader->loadRootResource('/a.php');
    }

    private function createTreeLoader(
        string $resourceBaseDir
    ): SiteKitNavigationTreeLoader {
        $resourceLoader = $this->createStub(
            ResourceLoader::class
        );
        $resourceLoader->method('load')
            ->willReturnCallback(static function ($location) use (
                $resourceBaseDir
            ) {
                return include $resourceBaseDir . $location;
            });
        $resourceLoader->method('exists')
            ->willReturnCallback(static function ($location) use (
                $resourceBaseDir
            ) {
                return file_exists($resourceBaseDir . $location);
            });
        return new SiteKitNavigationTreeLoader(
            $resourceLoader
        );
    }
}
