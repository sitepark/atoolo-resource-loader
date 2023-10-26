<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader;

use Atoolo\Resource\Exceptions\InvalidData;
use Atoolo\Resource\Loader\SiteKit\ContextStub;
use Atoolo\Resource\Loader\SiteKit\LifecylceStub;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLoader;

class SiteKitLoader implements ResourceLoader
{
    public function __construct(private string $base)
    {
    }

    public function load(string $location): Resource
    {
        $data = $this->loadRaw($this->base . '/' . $location);

        $this->validateData($data);

        return new Resource(
            $location,
            (string)$data['init']['id'],
            $data['init']['name'],
            $data['init']['objectType'],
            $data
        );
    }

    private function loadRaw(string $file): array
    {
        $context = new ContextStub();
        $lifecycle = new LifecylceStub();
        return include $file;
    }

    private function validateData(array $data): void
    {
        if (!isset($data['init']['id'])) {
            throw new InvalidData('id field missing');
        }
        if (!isset($data['init']['name'])) {
            throw new InvalidData('name field missing');
        }
        if (!isset($data['init']['objectType'])) {
            throw new InvalidData('objectType field missing');
        }
    }
}
