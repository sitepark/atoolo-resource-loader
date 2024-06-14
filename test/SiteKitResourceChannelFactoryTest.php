<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test;

use Atoolo\Resource\ResourceChannel;
use Atoolo\Resource\SiteKitResourceChannelFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SiteKitResourceChannelFactory::class)]
class SiteKitResourceChannelFactoryTest extends TestCase
{
    public function testCreateWithResourceLayout(): void
    {
        $baseDir = __DIR__ .
            '/resources/SiteKitResourceChannelFactory' .
            '/resourceLayout';
        $resourceDir = $baseDir . '/objects';
        $configDir = $baseDir . '/configs';

        $factory = new SiteKitResourceChannelFactory($baseDir);
        $channel = $factory->create();

        $expected = new ResourceChannel(
            '1',
            'Test',
            'test',
            'www.test.org',
            true,
            'internet',
            'de_DE',
            $baseDir,
            $resourceDir,
            $configDir,
            'test',
            []
        );
        $this->assertEquals(
            $expected,
            $channel,
            'ResourceChannel does not match expected values'
        );
    }

    public function testCreateWithDocumentRootLayout(): void
    {
        $baseDir = __DIR__ .
            '/resources/SiteKitResourceChannelFactory' .
            '/documentRootLayout';
        $resourceDir = $baseDir;
        $configDir = $baseDir;

        $factory = new SiteKitResourceChannelFactory($baseDir);
        $channel = $factory->create();

        $expected = new ResourceChannel(
            '1',
            'Test',
            'test',
            'www.test.org',
            true,
            'internet',
            'de_DE',
            $baseDir,
            $resourceDir,
            $configDir,
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
        $resourceDir = __DIR__ .
            '/resources/SiteKitResourceChannelFactory' .
            '/noexists';

        $factory = new SiteKitResourceChannelFactory($resourceDir);

        $this->expectException(\RuntimeException::class);
        $factory->create();
    }

    public function testCreateWithInvalidContextPhp(): void
    {
        $resourceDir = __DIR__ .
            '/resources/SiteKitResourceChannelFactory' .
            '/invalid';

        $factory = new SiteKitResourceChannelFactory($resourceDir);

        $this->expectException(\RuntimeException::class);
        $factory->create();
    }

    public function testEmptyBaseDIr(): void
    {
        $this->expectException(\RuntimeException::class);
        new SiteKitResourceChannelFactory('');
    }
}
