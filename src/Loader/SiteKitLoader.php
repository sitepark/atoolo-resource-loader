<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader;

use Atoolo\Resource\Exception\InvalidResourceException;
use Atoolo\Resource\Exception\ResourceNotFoundException;
use Atoolo\Resource\Loader\SiteKit\ContextStub;
use Atoolo\Resource\Loader\SiteKit\LifecylceStub;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceBaseLocator;
use Atoolo\Resource\ResourceLoader;

/**
 * ResourceLoader that loads resources created with SiteKit aggregators.
 * @phpstan-type InitData array{id: int, name: string, objectType: string}
 * @phpstan-type ResourceData array{init: InitData}
 */
class SiteKitLoader implements ResourceLoader
{
    public function __construct(
        private readonly ResourceBaseLocator $baseLocator
    ) {
    }

    /**
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
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

    public function exists(string $location): bool
    {
        return file_exists(
            $this->baseLocator->locate() .
                DIRECTORY_SEPARATOR .
            $location
        );
    }

    /**
     * @return array<string, mixed> $data
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     */
    private function loadRaw(string $location): array
    {
        $file = $this->baseLocator->locate() . '/' . $location;

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
            throw new InvalidResourceException(
                $location,
                $e->getMessage(),
                0,
                $e
            );
        } catch (\Error $e) {
            if (!file_exists($file)) {
                throw new ResourceNotFoundException(
                    $location,
                    $e->getMessage(),
                    0,
                    $e
                );
            }
            throw new InvalidResourceException(
                $location,
                $e->getMessage(),
                0,
                $e
            );
        } finally {
            error_reporting($saveErrorReporting);
        }
    }

    /**
     * @param array<string, mixed> $data
     * @return ($data is ResourceData ? void : never)
     * @throws InvalidResourceException
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
            throw new InvalidResourceException($location, 'init field missing');
        }
        // @codeCoverageIgnoreEnd

        $init = $data['init'];

        if (!isset($init['id'])) {
            throw new InvalidResourceException(
                $location,
                'id field missing'
            );
        }
        if (!is_int($init['id'])) {
            throw new InvalidResourceException(
                $location,
                'id field not an int'
            );
        }
        if (!isset($init['name'])) {
            throw new InvalidResourceException(
                $location,
                'name field missing'
            );
        }
        if (!is_string($init['name'])) {
            throw new InvalidResourceException(
                $location,
                'name field not a string'
            );
        }
        if (!isset($init['objectType'])) {
            throw new InvalidResourceException(
                $location,
                'objectType field missing'
            );
        }
        if (!is_string($init['objectType'])) {
            throw new InvalidResourceException(
                $location,
                'objectType field not a string'
            );
        }
    }
}
