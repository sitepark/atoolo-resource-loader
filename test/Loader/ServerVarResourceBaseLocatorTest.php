<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test\Loader;

use Atoolo\Resource\Loader\ResourceLayoutResourceBaseLocator;
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

    public function testWithoutSubDirectory(): void
    {
        $_SERVER['RESOURCE_ROOT'] = 'abc';
        $locator = new ServerVarResourceBaseLocator('RESOURCE_ROOT');
        $this->assertEquals(
            'abc',
            $locator->locate(),
            'unexpected resource base'
        );
    }

    public function testWithSubDirectory(): void
    {
        $_SERVER['RESOURCE_ROOT'] = 'abc';
        $locator = new ServerVarResourceBaseLocator(
            'RESOURCE_ROOT',
            'objects'
        );
        $this->assertEquals(
            'abc/objects',
            $locator->locate(),
            'unexpected resource base'
        );
    }

    public function testWithMissingSeverVariable(): void
    {
        $locator = new ServerVarResourceBaseLocator('RESOURCE_ROOT');

        $this->expectException(RuntimeException::class);
        $locator->locate();
    }
}
