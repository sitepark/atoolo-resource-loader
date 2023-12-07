<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test\Loader;

use Atoolo\Resource\Loader\DocumentRootLayoutResourceBaseLocator;
use Atoolo\Resource\Loader\ResourceLayoutResourceBaseLocator;
use Atoolo\Resource\Loader\StaticResourceBaseLocator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @backupGlobals enabled
 */
#[CoversClass(DocumentRootLayoutResourceBaseLocator::class)]
class DocumentRootLayoutResourceBaseLocatorTest extends TestCase
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
        $_SERVER['DOCUMENT_ROOT'] = 'abc';
        $locator = new DocumentRootLayoutResourceBaseLocator();
        $this->assertEquals(
            'abc',
            $locator->locate(),
            'unexpected resource base'
        );
    }

    public function testWithMissingSeverVariable(): void
    {
        $locator = new DocumentRootLayoutResourceBaseLocator();

        $this->expectException(RuntimeException::class);
        $locator->locate();
    }
}
