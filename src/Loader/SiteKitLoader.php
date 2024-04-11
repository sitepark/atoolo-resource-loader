<?php

declare(strict_types=1);

namespace Atoolo\Resource\Loader;

use Atoolo\Resource\Exception\InvalidResourceException;
use Atoolo\Resource\Exception\ResourceNotFoundException;
use Atoolo\Resource\Loader\SiteKit\ContextStub;
use Atoolo\Resource\Loader\SiteKit\LifecylceStub;
use Atoolo\Resource\Resource;
use Atoolo\Resource\ResourceBaseLocator;
use Atoolo\Resource\ResourceChannelFactory;
use Atoolo\Resource\ResourceLoader;
use Error;
use Locale;
use ParseError;

/**
 * ResourceLoader that loads resources created with SiteKit aggregators.
 * @phpstan-type InitData array{
 *     id: int,
 *     name: string,
 *     objectType: string,
 *     locale: string
 * }
 * @phpstan-type ResourceData array{init: InitData}
 */
class SiteKitLoader implements ResourceLoader
{
    /**
     * @var ?array<string, string> $langLocaleMap
     */
    private ?array $langLocaleMap = null;

    private string $resourceBase;

    public function __construct(
        private readonly ResourceBaseLocator $baseLocator,
        private readonly ResourceChannelFactory $resourceChannelFactory,
    ) {
        $this->resourceBase = $this->baseLocator->locate();
    }

    /**
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     */
    public function load(string $location, string $lang = ''): Resource
    {
        $data = $this->loadRaw($location, $lang);

        $data = $this->validateData($location, $data);

        $init = $data['init'];

        $locale = $init['locale'];
        $resourceLang = substr($locale, 0, 2);

        return new Resource(
            $location,
            (string)$init['id'],
            $init['name'],
            $init['objectType'],
            $resourceLang,
            $data
        );
    }

    public function exists(string $location, string $lang = ''): bool
    {
        return file_exists(
            $this->locationToFile($location, $lang)
        );
    }

    private function locationToFile(string $location, string $lang): string
    {
        $file = $this->resourceBase . '/' . $location;
        $locale = $this->langToLocale($lang);
        if (empty($locale)) {
            return $file;
        }

        return $file . '.translations/' . $locale . '.php';
    }

    /**
     * @return array<string,mixed>
     * @throws InvalidResourceException
     * @throws ResourceNotFoundException
     */
    private function loadRaw(string $location, string $lang): array
    {
        $file = $this->locationToFile($location, $lang);

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
            ob_start();
            $data = require $file;
            if (!is_array($data)) {
                    throw new InvalidResourceException(
                        $location,
                        'The resource should return an array'
                    );
            }
            /* @var ResourceData $data */
            return $data;
        } catch (ParseError $e) {
            throw new InvalidResourceException(
                $location,
                $e->getMessage(),
                0,
                $e
            );
        } catch (Error $e) {
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
            ob_end_clean();
            error_reporting($saveErrorReporting);
        }
    }

    private function langToLocale(string $lang): string
    {
        if (empty($lang)) {
            return $lang;
        }

        $this->langLocaleMap ??= $this->createLangLocaleMap();

        return $this->langLocaleMap[$lang] ?? '';
    }

    /**
     * @return array<string, string>
     */
    private function createLangLocaleMap(): array
    {
        $map = [];
        $resourceChannel = $this->resourceChannelFactory->create();
        foreach (
            $resourceChannel->translationLocales as $availableLocale
        ) {
            $primaryLang = Locale::getPrimaryLanguage($availableLocale);
            $map[$primaryLang] = $availableLocale;
        }
        return $map;
    }

    /**
     * @param array<string,mixed> $data
     * @return ResourceData
     * @throws InvalidResourceException
     */
    private function validateData(string $location, array $data): array
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
        if (!isset($init['locale'])) {
            throw new InvalidResourceException(
                $location,
                'locale field missing'
            );
        }
        if (!is_string($init['locale'])) {
            throw new InvalidResourceException(
                $location,
                'locale field not a string'
            );
        }

        /** @var ResourceData $data */
        return $data;
    }
}
