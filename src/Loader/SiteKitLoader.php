<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader;

use Atoolo\Resource\Exceptions\InvalidResource;
use Atoolo\Resource\Exceptions\ResourceNotFound;
use Atoolo\Resource\Loader\SiteKit\ContextStub;
use Atoolo\Resource\Loader\SiteKit\LifecylceStub;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceLoader;

/**
 * ResourceLoader that loads resources created with SiteKit aggregators.
 */
class SiteKitLoader implements ResourceLoader
{
    public function __construct(
        private readonly string $base
    ) {
    }

    public function load(string $location): Resource
    {
        $data = $this->loadRaw($location);

        $this->validateData($location, $data);

        $init = (array)$data['init'];

        return new Resource(
            $location,
            (string)$init['id'],  // @phpstan-ignore-line
            $init['name'], // @phpstan-ignore-line
            $init['objectType'], // @phpstan-ignore-line
            $data
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function loadRaw(string $location): array
    {
        $file = $this->base . '/' . $location;

        $context = new ContextStub();
        $lifecycle = new LifecylceStub();

        $saveErrorReporting = error_reporting();

        try {
            error_reporting(E_ERROR | E_PARSE);
            return require $file;
        } catch (\ParseError $e) {
            throw new InvalidResource($location, $e->getMessage(), 0, $e);
        } catch (\Error $e) {
            if (!file_exists($file)) {
                throw new ResourceNotFound($location, $e->getMessage(), 0, $e);
            }
            throw new InvalidResource($location, $e->getMessage(), 0, $e);
        } finally {
            error_reporting($saveErrorReporting);
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function validateData(string $location, array $data): void
    {
        $init = $data['init'];
        if (!is_array($init)) {
            throw new InvalidResource($location, 'missing init array');
        }

        if (!isset($init['id'])) {
            throw new InvalidResource($location, 'id field missing');
        }
        if (!isset($init['name'])) {
            throw new InvalidResource($location, 'name field missing');
        }
        if (!isset($init['objectType'])) {
            throw new InvalidResource($location, 'objectType field missing');
        }
    }
}
