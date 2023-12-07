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
    public function testConstruct(): void
    {
        $saveServerState = $_SERVER;
        try {
            $_SERVER['DOCUMENT_ROOT'] = 'abc';
            $locator = new DocumentRootLayoutResourceBaseLocator();
            $this->assertEquals(
                'abc',
                $locator->locate(),
                'unexpected resource base'
            );
        } finally {
            $_SERVER = $saveServerState;
        }
    }

    public function testWithMissingSeverVariable(): void
    {
        $locator = new DocumentRootLayoutResourceBaseLocator();

        $this->expectException(RuntimeException::class);
        $locator->locate();
    }
}
