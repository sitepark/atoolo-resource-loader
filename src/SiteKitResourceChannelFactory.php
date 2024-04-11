<?php

declare(strict_types=1);

namespace Atoolo\Resource;

use RuntimeException;

/**
 * @phpstan-type ContextPhp array{
 *     publisher: array{
 *          id: int,
 *          name: string,
 *          anchor: string,
 *          serverName: string,
 *          preview: bool,
 *          nature: string,
 *          locale: ?string,
 *          encoding: ?string,
 *          translationLocales: ?string[]
 *     }
 * }
 */
class SiteKitResourceChannelFactory implements ResourceChannelFactory
{
    private ?ResourceChannel $resourceChannel = null;

    public function __construct(
        private readonly ResourceBaseLocator $resourceBaseLocator
    ) {
    }

    public function create(): ResourceChannel
    {

        if ($this->resourceChannel !== null) {
            return $this->resourceChannel;
        }

        $data = $this->loadContextPhpFile();

        $searchIndex = str_replace(
            '.',
            '-',
            $data['publisher']['anchor']
        );
        $this->resourceChannel = new ResourceChannel(
            (string)$data['publisher']['id'],
            $data['publisher']['name'],
            $data['publisher']['anchor'],
            $data['publisher']['serverName'],
            $data['publisher']['preview'],
            $data['publisher']['nature'],
            $data['publisher']['locale'] ?? 'de_DE',
            $data['publisher']['encoding'] ?? 'UTF-8',
            $searchIndex,
            $data['publisher']['translationLocales'] ?? [],
        );

        return $this->resourceChannel;
    }

    /**
     * @return ContextPhp
     */
    private function loadContextPhpFile(): array
    {
        $contextPhpFile = $this->findContextPhpFile();
        $context = require $contextPhpFile;
        if (!is_array($context)) {
            throw new RuntimeException(
                'context.php must return an array'
            );
        }

        /** @var ContextPhp $context */
        return $context;
    }

    private function findContextPhpFile(): string
    {
        $resourceBase = $this->resourceBaseLocator->locate();
        $resourceLayoutContextPhpFile = dirname($resourceBase) . '/context.php';

        if (file_exists($resourceLayoutContextPhpFile)) {
            return $resourceLayoutContextPhpFile;
        }

        $documentRootLayoutContextPhpFile =
            $resourceBase . '/WEB-IES/context.php';

        if (!file_exists($documentRootLayoutContextPhpFile)) {
            throw new RuntimeException(
                'context.php does not exists: ' .
                $resourceLayoutContextPhpFile . ' or ' .
                $documentRootLayoutContextPhpFile
            );
        }
        return $documentRootLayoutContextPhpFile;
    }
}
