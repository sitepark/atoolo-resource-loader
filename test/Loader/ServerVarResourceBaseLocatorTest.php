<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test\Loader;

use Atoolo\Resource\Loader\ServerVarResourceBaseLocator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(ServerVarResourceBaseLocator::class)]
class ServerVarResourceBaseLocatorTest extends TestCase
{
    /**
     * @var array<string,string>
     */
    private array $saveServerState;

    public function setUp(): void
    {
        $this->saveServerState = $_SERVER;
    }

    public function tearDown(): void
    {
        $_SERVER = $this->saveServerState;
    }

    public function testWithDocumentRootLayout(): void
    {
        $baseLocation =
            __DIR__ .
            '/../resources/Loader/ServerVarResourceBaseLocator' .
            '/documentRootLayout';
        $_SERVER['RESOURCE_ROOT'] = $baseLocation;

        $locator = new ServerVarResourceBaseLocator('RESOURCE_ROOT');
        $this->assertEquals(
            $baseLocation,
            $locator->locate(),
            'unexpected resource base'
        );
    }

    public function testWithResourceLayout(): void
    {
        $baseLocation =
            __DIR__ .
            '/../resources/Loader/ServerVarResourceBaseLocator' .
            '/resourceLayout';
        $_SERVER['RESOURCE_ROOT'] = $baseLocation;
        $locator = new ServerVarResourceBaseLocator(
            'RESOURCE_ROOT'
        );
        $this->assertEquals(
            $baseLocation . '/object',
            $locator->locate(),
            'unexpected resource base'
        );
    }

    public function testWithInvalidDirectory(): void
    {
        $baseLocation =
            __DIR__ .
            '/../resources/Loader/ServerVarResourceBaseLocator' .
            '/invalid';
        $_SERVER['RESOURCE_ROOT'] = $baseLocation;
        $locator = new ServerVarResourceBaseLocator(
            'RESOURCE_ROOT'
        );
        $this->expectException(RuntimeException::class);
        $locator->locate();
    }

    public function testWithMissingSeverVariable(): void
    {
        $locator = new ServerVarResourceBaseLocator('RESOURCE_ROOT');

        $this->expectException(RuntimeException::class);
        $locator->locate();
    }

    public function testUseCache(): void
    {
        $baseLocation =
            __DIR__ .
            '/../resources/Loader/ServerVarResourceBaseLocator' .
            '/documentRootLayout';
        $_SERVER['RESOURCE_ROOT'] = $baseLocation;

        $locator = new ServerVarResourceBaseLocator('RESOURCE_ROOT');
        $locator->locate();

        $this->assertEquals(
            $baseLocation,
            $locator->locate(),
            'unexpected resource base'
        );
    }
}
