<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader\SiteKit;

class LifecylceStub
{
    public function init(array $data): ResourceStub
    {
        $resource = new ResourceStub();
        $resource->init($data);
        return $resource;
    }

    public function finish(ResourceStub $resource): bool
    {
        return false;
    }

    public function process(string $name, ResourceStub $resource): bool
    {
        return true;
    }

    public function service(ResourceStub $resource): array
    {
        return $resource->getData();
    }
}
