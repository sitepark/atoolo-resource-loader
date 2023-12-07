<?php

declare(strict_types=1);

namespace Atoolo\Resource\Test\Loader;

use Atoolo\Resource\Loader\SiteKitLoader;
use Atoolo\Resource\Loader\StaticResourceBaseLocator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(StaticResourceBaseLocator::class)]
class StaticResourceBaseLocatorTest extends TestCase
{
    public function testConstruct(): void
    {
        $locator = new StaticResourceBaseLocator('abc');
        $this->assertEquals(
            'abc',
            $locator->locate(),
            'unexpected resource base'
        );
    }
}
