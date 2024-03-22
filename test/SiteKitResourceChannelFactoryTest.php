<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test;

use Atoolo\Resource\ResourceBaseLocator;
use Atoolo\Resource\ResourceChannel;
use Atoolo\Resource\SiteKitResourceChannelFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SiteKitResourceChannelFactory::class)]
class SiteKitResourceChannelFactoryTest extends TestCase
{
    public function testCreateWithResourceLayout(): void
    {
        $resourceBaseLocator = $this->createStub(
            ResourceBaseLocator::class
        );
        $resourceDir = __DIR__ .
            '/resources/SiteKitResourceChannelFactory' .
            '/resourceLayout';
        $resourceBaseLocator->method('locate')
            ->willReturn($resourceDir . '/objects');

        $factory = new SiteKitResourceChannelFactory($resourceBaseLocator);
        $channel = $factory->create();

        $expected = new ResourceChannel(
            '1',
            'Test',
            'test',
            'www.test.org',
            true,
            'internet',
            'de_DE',
            'UTF-8',
            'test',
            []
        );
        $this->assertEquals(
            $expected,
            $channel,
            'ResourceChannel does not match expected values'
        );
    }

    public function testCreateCache(): void
    {
        $resourceBaseLocator = $this->createMock(
            ResourceBaseLocator::class
        );
        $resourceDir = __DIR__ .
            '/resources/SiteKitResourceChannelFactory' .
            '/resourceLayout';
        $resourceBaseLocator
            ->expects($this->once())
            ->method('locate')
            ->willReturn($resourceDir . '/objects');

        $factory = new SiteKitResourceChannelFactory($resourceBaseLocator);
        $factory->create();
        $factory->create();
    }

    public function testCreateWithDocumentRootLayout(): void
    {
        $resourceBaseLocator = $this->createStub(
            ResourceBaseLocator::class
        );
        $resourceDir = __DIR__ .
            '/resources/SiteKitResourceChannelFactory' .
            '/documentRootLayout';
        $resourceBaseLocator->method('locate')
            ->willReturn($resourceDir);

        $factory = new SiteKitResourceChannelFactory($resourceBaseLocator);
        $channel = $factory->create();

        $expected = new ResourceChannel(
            '1',
            'Test',
            'test',
            'www.test.org',
            true,
            'internet',
            'de_DE',
            'UTF-8',
            'test',
            []
        );
        $this->assertEquals(
            $expected,
            $channel,
            'ResourceChannel does not match expected values'
        );
    }

    public function testCreateNonExistsContextPhp(): void
    {
        $resourceBaseLocator = $this->createStub(
            ResourceBaseLocator::class
        );
        $resourceDir = __DIR__ .
            '/resources/SiteKitResourceChannelFactory' .
            '/noexists';
        $resourceBaseLocator->method('locate')
            ->willReturn($resourceDir);

        $factory = new SiteKitResourceChannelFactory($resourceBaseLocator);

        $this->expectException(\RuntimeException::class);
        $factory->create();
    }

    public function testCreateWithInvalidContextPhp(): void
    {
        $resourceBaseLocator = $this->createStub(
            ResourceBaseLocator::class
        );
        $resourceDir = __DIR__ .
            '/resources/SiteKitResourceChannelFactory' .
            '/invalid';
        $resourceBaseLocator->method('locate')
            ->willReturn($resourceDir . '/objects');

        $factory = new SiteKitResourceChannelFactory($resourceBaseLocator);

        $this->expectException(\RuntimeException::class);
        $factory->create();
    }
}
