<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test\Loader;

use Atoolo\Resource\Exception\InvalidResourceException;
use Atoolo\Resource\Exception\ResourceNotFoundException;
use Atoolo\Resource\Loader\SiteKitLoader;
use Atoolo\Resource\Loader\StaticResourceBaseLocator;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceChannel;
use Atoolo\Resource\ResourceChannelFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SiteKitLoader::class)]
class SiteKitLoaderTest extends TestCase
{
    private SiteKitLoader $loader;

    protected function setUp(): void
    {
        $resourceDir = realpath(__DIR__ . '/../resources/Loader/SiteKitLoader');
        $channel = new ResourceChannel(
            '1',
            'Test Channel',
            'test-www',
            'test-server',
            false,
            'internet',
            'de_DE',
            '',
            $resourceDir,
            'test-www',
            ['en_US']
        );
        $this->loader = new SiteKitLoader($channel);
    }

    public function testExists(): void
    {
        $exists = $this->loader->exists('validResource.php');
        $this->assertTrue($exists, 'resource should exist');
    }

    public function testExistsWithLang(): void
    {
        $exists = $this->loader->exists('validResource.php', 'en');
        $this->assertTrue($exists, 'resource should exist');
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

    public function testLoadValidResourceWithLang(): void
    {
        $resource = $this->loader->load('validResource.php', 'en');
        $this->assertEquals(
            '1118',
            $resource->getId(),
            'unexpected id'
        );
    }

    public function testLoadMissingLocation(): void
    {
        $this->expectException(ResourceNotFoundException::class);
        $this->loader->load('notfound.php');
    }

    public function testLoadResourceWithCompileError(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->loader->load('compileError.php');
    }

    public function testLoadResourceWithCommonError(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->loader->load('commonError.php');
    }

    public function testLoadWithMissingInit(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->loader->load('missingInitResource.php');
    }

    public function testLoadWithInitNotAnArray(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->loader->load('initNotAnArrayResource.php');
    }

    public function testLoadWithNonIntId(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->loader->load('nonIntIdResource.php');
    }

    public function testLoadWithMissingId(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->loader->load('missingIdResource.php');
    }

    public function testLoadWithMissingName(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->loader->load('missingNameResource.php');
    }

    public function testLoadWithNonStringName(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->loader->load('nonStringNameResource.php');
    }

    public function testLoadWithMissingObjectType(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->loader->load('missingObjectTypeResource.php');
    }

    public function testLoadWithNonStringObjectType(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->loader->load('nonStringObjectTypeResource.php');
    }

    public function testLoadWithMissingLocale(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->loader->load('missingLocaleResource.php');
    }

    public function testLoadWithNonStringLocale(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->loader->load('nonStringLocaleResource.php');
    }

    public function testLoadWithNonArrayReturned(): void
    {
        $this->expectException(InvalidResourceException::class);
        $this->loader->load('nonArrayReturned.php');
    }
}
