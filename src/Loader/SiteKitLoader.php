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
 * @phpstan-type InitData array{id: int, name: string, objectType: string}
 * @phpstan-type ResourceData array{init: InitData}
 */
class SiteKitLoader implements ResourceLoader
{
    public function __construct(
        private readonly string $basePath
    ) {
    }

    /**
     * @throws InvalidResource
     * @throws ResourceNotFound
     */
    public function load(string $location): Resource
    {
        $data = $this->loadRaw($location);

        $this->validateData($location, $data);

        $init = $data['init'];

        return new Resource(
            $location,
            (string)$init['id'],
            $init['name'],
            $init['objectType'],
            $data
        );
    }

    /**
     * @return array<string, mixed> $data
     */
    private function loadRaw(string $location): array
    {
        $file = $this->basePath . '/' . $location;

        /**
         * $context and $lifecycle must be defined here, because for the SiteKit
         * resource PHP files these variables must be provided for the require
         * call.
         */
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
     * @return ($data is ResourceData ? void : never)
     */
    private function validateData(string $location, array $data): void
    {

        /*
         * Cannot be passed because this case cannot occur here. This would
         * already lead to an error in ResourceStub. But is still included,
         * that so no phpstan errors arise.
         */
        // @codeCoverageIgnoreStart
        if (!isset($data['init']) || !is_array($data['init'])) {
            throw new InvalidResource($location, 'init field missing');
        }
        // @codeCoverageIgnoreEnd

        $init = $data['init'];

        if (!isset($init['id'])) {
            throw new InvalidResource(
                $location,
                'id field missing'
            );
        }
        if (!is_int($init['id'])) {
            throw new InvalidResource(
                $location,
                'id field not an int'
            );
        }
        if (!isset($init['name'])) {
            throw new InvalidResource(
                $location,
                'name field missing'
            );
        }
        if (!is_string($init['name'])) {
            throw new InvalidResource(
                $location,
                'name field not a string'
            );
        }
        if (!isset($init['objectType'])) {
            throw new InvalidResource(
                $location,
                'objectType field missing'
            );
        }
        if (!is_string($init['objectType'])) {
            throw new InvalidResource(
                $location,
                'objectType field not a string'
            );
        }
    }
}
