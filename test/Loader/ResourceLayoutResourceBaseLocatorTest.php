<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test\Loader;

use Atoolo\Resource\Loader\DocumentRootLayoutResourceBaseLocator;
use Atoolo\Resource\Loader\ResourceLayoutResourceBaseLocator;
use Atoolo\Resource\Loader\StaticResourceBaseLocator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(ResourceLayoutResourceBaseLocator::class)]
class ResourceLayoutResourceBaseLocatorTest extends TestCase
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

    public function testConstruct(): void
    {
        $_SERVER['RESOURCE_ROOT'] = 'abc';
        $locator = new ResourceLayoutResourceBaseLocator();
        $this->assertEquals(
            'abc/objects',
            $locator->locate(),
            'unexpected resource base'
        );
    }

    public function testWithMissingSeverVariable(): void
    {
        $locator = new ResourceLayoutResourceBaseLocator();

        $this->expectException(RuntimeException::class);
        $locator->locate();
    }
}
